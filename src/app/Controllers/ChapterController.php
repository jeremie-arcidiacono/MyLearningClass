<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a controller used to manage the chapters of a course.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Controllers;

use App\App;
use App\Contracts\ISession;
use App\Enums\Action;
use App\Enums\ChapterProgressStatus;
use App\Exceptions\ForbiddenHttpException;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Media;
use App\Services\ChapterProgressService;
use App\Services\ChapterService;
use App\Services\CourseEnrollmentService;
use App\Validator;

/**
 * Manage the chapters of a course.
 */
class ChapterController
{
    public const CHAPTER_MEDIA_PATH = STORAGE_PATH . '/assets/courses/chaptersContent';

    /**
     * Show the lesson page.
     * @param Course $course
     * @param int $chapterPosition The chapter number relative to the course. (1 = first chapter)
     * @return string
     * @throws ForbiddenHttpException
     */
    public function show(Course $course, int $chapterPosition = 1): string
    {
        $user = App::$auth->getUser();
        if (!CourseEnrollmentService::isEnrolled($user, $course)) {
            throw new ForbiddenHttpException('Vous ne pouvez pas accéder à ce cours.');
        }

        $chapter = ChapterService::FindByCourseAndPosition($course, $chapterPosition);

        return App::$templateEngine->run(
            'course.lesson',
            [
                'course' => $course,
                'chapter' => $chapter,
            ]
        );
    }

    /**
     * Render the video of the chapter.
     * @param Course $course
     * @param Chapter $chapter
     * @return string
     * @throws ForbiddenHttpException
     */
    public function renderVideo(Course $course, Chapter $chapter): string
    {
        $user = App::$auth->getUser();
        if (!CourseEnrollmentService::isEnrolled($user, $course) && $user->getId() !== $course->getOwner()->getId()) {
            throw new ForbiddenHttpException('Vous ne pouvez pas accéder à ce cours.');
        }

        if (!$course->getChapters()->contains($chapter)) {
            throw new ForbiddenHttpException('Vous ne pouvez pas accéder à ce chapitre.');
        }

        $filePath = self::CHAPTER_MEDIA_PATH . '/' . $chapter->getVideo()->getFilename();

        if (!file_exists($filePath)) {
            throw new \Exception("Erreur interne. Le fichier n'existe pas.");
        }


        $mime = $chapter->getVideo()->getMimeType();

        App::$response->httpCode(200)
            ->header("Content-Type: $mime")
            ->header('Content-Length:' . filesize($filePath))
            ->header('Cache-Control: private, max-age=86400')
            ->render($filePath);
    }

    /**
     * Send the ressource file of the chapter to the client as a download.
     * @param Course $course
     * @param Chapter $chapter
     * @return string
     * @throws ForbiddenHttpException
     */
    public function downloadRessource(Course $course, Chapter $chapter): string
    {
        $user = App::$auth->getUser();
        if (!CourseEnrollmentService::isEnrolled($user, $course) && $user->getId() !== $course->getOwner()->getId()) {
            throw new ForbiddenHttpException('Vous ne pouvez pas accéder à ce cours.');
        }

        if (!$course->getChapters()->contains($chapter)) {
            throw new ForbiddenHttpException('Vous ne pouvez pas accéder à ce chapitre.');
        }

        $filePath = self::CHAPTER_MEDIA_PATH . '/' . $chapter->getRessource()->getFilename();

        if (!file_exists($filePath)) {
            throw new \Exception("Erreur interne. Le fichier n'existe pas.");
        }


        $mime = $chapter->getRessource()->getMimeType();

        App::$response->httpCode(200)
            ->header("Content-Type: $mime")
            ->header('Content-Length:' . filesize($filePath))
            ->header('Content-Disposition: attachment; filename="' . $chapter->getRessource()->getFilename() . '"')
            ->header('Cache-Control: private, max-age=86400')
            ->render($filePath);
    }

    /**
     * Update the chapter progression of the authenticated user.
     * @param Course $course
     * @param Chapter $chapter
     * @return string
     * @throws ForbiddenHttpException
     */
    public function updateProgression(Course $course, Chapter $chapter): never
    {
        $user = App::$auth->getUser();
        if (!CourseEnrollmentService::isEnrolled($user, $course)) {
            throw new ForbiddenHttpException('Vous ne pouvez pas accéder à ce cours.');
        }

        if (!$course->getChapters()->contains($chapter)) {
            throw new ForbiddenHttpException('Vous ne pouvez pas accéder à ce chapitre.');
        }

        $inputs = getAllInputs();

        $validatedInputs = new Validator(
            $inputs,
            [
                'chapterProgressState' => ['required', 'enum:' . ChapterProgressStatus::class],
            ]
        );

        if (!$validatedInputs->isValid()) {
            App::$session->setFlash(ISession::ERROR_KEY, ['Le nouveau statut de progression du chapitre est invalide.']);
        }
        else {
            $chapterProgressStatus = ChapterProgressStatus::from($inputs['chapterProgressState']);
            $chapterProgress = ChapterProgressService::Find($user, $chapter);
            $chapterProgress->setStatus($chapterProgressStatus);
            ChapterProgressService::Update($chapterProgress);
        }

        redirect(url('chapter.show', ['courseId' => $course->getId(), 'chapter' => $chapter->getPosition()]));
    }

    public function store(Course $course): string
    {
        if (!App::$auth->can(Action::Update, $course)) { // The action of adding a chapter is considered as an update of the course.
            throw new ForbiddenHttpException('Vous ne pouvez pas créer de chapitre.');
        }

        $inputs = getAllInputs();

        $rules = [
            'titre' => ['required', 'lenmin:5', 'lenmax:100'],
        ];

        $validatedInputs = new Validator($inputs, $rules);

        if (!$validatedInputs->isValid()) {
            App::$session->setFlash(ISession::ERROR_KEY, $validatedInputs->getErrors());
            return App::$templateEngine->run('course.config', ['course' => $course]);
        }

        // Check the video
        $fileVideo = App::$request->getInputHandler()->file('video');
        if ($fileVideo->hasError() || !in_array(strtolower($fileVideo->getMime()), App::$config->get('models.chapter.videoAllowedMimeTypes', []))) {
            // The video is invalid

            App::$session->setFlash(ISession::ERROR_KEY, ['Video' => "Le fichier n'est pas une vidéo valide avec un format valide"]);
            return App::$templateEngine->run('course.config', ['course' => $course]);
        }

        $media = (new Media())
            ->setName($fileVideo->getFilename())
            ->setMimeType($fileVideo->getMime())
            ->setFilename(uniqid(more_entropy: true) . '.' . $fileVideo->getExtension());

        if (!$fileVideo->move(self::CHAPTER_MEDIA_PATH . '/' . $media->getFilename())) {
            // The video can't be saved as a file on the server

            App::$session->setFlash(ISession::ERROR_KEY, ['Video' => "Une erreur est survenue lors de l'upload de la vidéo"]);
            return App::$templateEngine->run('course.config', ['course' => $course]);
        }


        // Check the ressource
        $fileRessource = App::$request->getInputHandler()->file('ressource');
        if ($fileRessource->hasError() || !in_array(strtolower($fileRessource->getMime()), App::$config->get('models.chapter.ressourceAllowedMimeTypes', []))) {
            // The ressource is invalid

            App::$session->setFlash(ISession::ERROR_KEY, ['Ressource' => "Le fichier n'est pas une document valide avec un format valide"]);
            return App::$templateEngine->run('course.config', ['course' => $course]);
        }

        $ressource = (new Media())
            ->setName($fileRessource->getFilename())
            ->setMimeType($fileRessource->getMime())
            ->setFilename(uniqid(more_entropy: true) . '.' . $fileRessource->getExtension());

        if (!$fileRessource->move(self::CHAPTER_MEDIA_PATH . '/' . $ressource->getFilename())) {
            // The ressource can't be saved as a file on the server

            App::$session->setFlash(ISession::ERROR_KEY, ['Ressource' => "Une erreur est survenue lors de l'upload du document"]);
            return App::$templateEngine->run('course.config', ['course' => $course]);
        }

        $chapter = (new Chapter())
            ->setTitle($inputs['titre'])
            ->setVideo($media)
            ->setRessource($ressource)
            ->setCourse($course)
            ->setPosition($course->getChapters()->count() + 1);

        ChapterService::Create($chapter);

        redirect(url('course.edit', ['courseId' => $course->getId()]));
    }
}
