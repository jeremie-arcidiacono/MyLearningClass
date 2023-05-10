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
use App\Enums\ChapterProgressStatus;
use App\Exceptions\ForbiddenHttpException;
use App\Models\Chapter;
use App\Models\Course;
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
}
