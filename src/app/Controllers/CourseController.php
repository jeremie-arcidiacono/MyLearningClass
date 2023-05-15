<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a controller used to manage the courses.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Controllers;

use App\App;
use App\Contracts\ISession;
use App\Enums\Action;
use App\Enums\ChapterProgressStatus;
use App\Enums\CourseVisibility;
use App\Exceptions\ForbiddenHttpException;
use App\Models\ChapterProgress;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Media;
use App\Services\ChapterProgressService;
use App\Services\CourseCategoryService;
use App\Services\CourseEnrollmentService;
use App\Services\CourseService;
use App\Services\MediaService;
use App\Services\Service;
use App\Services\UserService;
use App\Validator;
use Doctrine\ORM\Exception\ORMException;

/**
 *  Manage the courses.
 */
class CourseController
{
    public const COURSE_BANNER_IMG_PATH = STORAGE_PATH . '/assets/courses/banners';

    /**
     * Show a list of paginated and filtered courses.
     * @return string
     */
    public function index(): string
    {
        $filters = getAllInputs();

        $rules = [
            'page' => ['integer', 'min:1'],
            'recherche' => ['string', 'lenmin:1'],
            'categorie' => ['integer', 'exists:' . CourseCategory::class],
        ];

        $validator = new Validator($filters, $rules, App::$db);
        if ($validator->isValid()) {
            $search = $filters['recherche'] ?? null;
            if (isset($filters['categorie']) && $filters['categorie'] !== '') {
                $category = CourseCategoryService::Find((int)$filters['categorie']);
            }
            else {
                $category = null;
            }
            $page = isset($filters['page']) ? (int)$filters['page'] : 1;
        }
        else {
            $search = null;
            $category = null;
            $page = 1;
        }

        $coursesPaginator = CourseService::FindByWithPagination(
            $page,
            unescape($search),
            $category,
        );

        $courses = $coursesPaginator->getIterator()->getArrayCopy();

        flashInputsOnly(['recherche', 'categorie']);

        return App::$templateEngine->run(
            'course.index',
            [
                'nbCourseAvailable' => $coursesPaginator->getTotalItems(),
                'courses' => $courses,
                'currentPage' => $coursesPaginator->getCurrentPage(),
                'totalPages' => $coursesPaginator->getTotalPage(),
                'categories' => CourseCategoryService::FindAll(),
            ]
        );
    }

    /**
     * Show the course detail page.
     * @param Course $course
     * @return string
     * @throws ForbiddenHttpException
     */
    public function show(Course $course): string
    {
        if (!$this->userCanSeeCourse($course)) {
            throw new ForbiddenHttpException('Vous ne pouvez pas accéder à ce cours.');
        }

        return App::$templateEngine->run(
            'course.show',
            [
                'course' => $course
            ]
        );
    }


    /**
     * Send the image of the course banner.
     * @param Course $course
     * @return never
     * @throws ForbiddenHttpException
     */
    public function renderBannerImg(Course $course): never
    {
        if (!$this->userCanSeeCourse($course)) {
            throw new ForbiddenHttpException('Vous ne pouvez pas accéder à ce cours.');
        }

        $filePath = self::COURSE_BANNER_IMG_PATH . '/' . $course->getBanner()->getFilename();

        if (!file_exists($filePath)) {
            throw new \Exception("Erreur interne. L'image n'existe pas.");
        }


        $mime = $course->getBanner()->getMimeType();

        App::$response->httpCode(200)
            ->header("Content-Type: $mime")
            ->header('Content-Length:' . filesize($filePath))
            //->header('Cache-Control: private, max-age=86400')
//            ->header('Cache-Control: private, max-age=0, stale-while-revalidate=3600')
            //  the client serve the cached image for 1 hour while it revalidates the image with the server in the background.

            // To do : remake the cache control (temporary disabled to ensure the image is updated when the user change it)
            ->header('Cache-Control: no-cache, no-store, must-revalidate')
            ->render($filePath);
    }

    /**
     * Check if the current logged user (or a guest) can see the course (only the public info, not the chapters videos/resources).
     * @param Course $course
     * @return bool
     */
    protected function userCanSeeCourse(Course $course): bool
    {
        if (!App::$auth->check()) {
            // The user is not logged in, so he can see only the public courses.
            if ($course->getVisibility() !== CourseVisibility::Public) {
                return false;
            }
        }
        elseif ($course->getVisibility() === CourseVisibility::Private) {
            // The course is private, so the user must be enrolled or be a teacher.
            if (!(App::$auth->getUser()->getId() === $course->getOwner()->getId()) &&
                !(CourseEnrollmentService::isEnrolled(App::$auth->getUser(), $course))) {
                return false;
            }
        }
        elseif ($course->getVisibility() === CourseVisibility::Draft) {
            // The course is a draft, so the user must be the owner.
            if (!(App::$auth->getUser()->getId() === $course->getOwner()->getId())) {
                return false;
            }
        }

        // The user can see the course.
        return true;
    }

    /**
     * Enroll the authenticated user to the course.
     * @param Course $course
     * @return never
     * @throws ForbiddenHttpException If the course is not public.
     */
    public function enroll(Course $course): never
    {
        $user = App::$auth->getUser();

        if ($course->getVisibility() !== CourseVisibility::Public) {
            throw new ForbiddenHttpException('Vous ne pouvez pas vous inscrire à ce cours.');
        }
        elseif (CourseEnrollmentService::isEnrolled($user, $course)) {
            App::$session->setFlash(ISession::ERROR_KEY, ['Inscription' => 'Vous êtes déjà inscrit à ce cours.']);
            redirect(url('course.show', ['courseId' => $course->getId()]));
        }
        else {
            $user->createEnrollment($course);

            // Create his progress for each chapter (set to 'to do').
            $chapters = $course->getChapters();
            foreach ($chapters as $chapter) {
                $chapterProgress = new ChapterProgress();
                $chapterProgress->setChapter($chapter);
                $chapterProgress->setStatus(ChapterProgressStatus::ToDo);
                $user->addChapterProgress($chapterProgress);
            }

            UserService::Update($user);
            redirect(url('chapter.show', ['courseId' => $course->getId()]));
        }
    }

    /**
     * Unenroll the authenticated user from the course.
     * @param Course $course
     * @return never
     * @throws ForbiddenHttpException If the user has not the permission to unenroll.
     * @throws \Exception If the user is not enrolled.
     */
    public function unenroll(Course $course): never
    {
        $user = App::$auth->getUser();

        if (!CourseEnrollmentService::isEnrolled($user, $course)) {
            throw new \Exception('Vous n\'êtes pas inscrit à ce cours.');
        }

        $enrollment = CourseEnrollmentService::FindByUserAndCourse($user, $course);

        if (!App::$auth->can(Action::Delete, $enrollment)) {
            throw new ForbiddenHttpException('Vous n\'avez pas la permission de supprimer cette inscription.');
        }

        CourseEnrollmentService::Delete(CourseEnrollmentService::FindByUserAndCourse($user, $course), false);

        $chaptersProgress = ChapterProgressService::FindByUserAndCourse($user, $course);

        foreach ($chaptersProgress as $chapterProgress) {
            ChapterProgressService::Delete($chapterProgress, false);
        }

        Service::Flush();

        redirect(url('course.show', ['courseId' => $course->getId()]));
    }

    /**
     * Add the course to the authenticated user's bookmarks.
     * @param Course $course
     * @return never
     * @throws ForbiddenHttpException If the course is not public.
     */
    public function bookmark(Course $course): never
    {
        if ($course->getVisibility() !== CourseVisibility::Public) {
            throw new ForbiddenHttpException('Vous ne pouvez pas ajouter ce cours à vos favoris car il n\'est pas public.');
        }

        $user = App::$auth->getUser();

        if ($user->getBookmarkedCourses()->contains($course)) {
            App::$session->setFlash(ISession::ERROR_KEY, ['Favoris' => 'Ce cours est déjà dans vos favoris.']);
        }
        else {
            $user->addBookmarkedCourse($course);
            UserService::Update($user);
        }

        if (isset(getAllInputs()['redirect']) &&
            getAllInputs()['redirect'] !== '') {
            redirect(unescape(getAllInputs()['redirect']));
        }
        else {
            redirect(url('course.show', ['courseId' => $course->getId()]));
        }
    }

    /**
     * Remove the course from the authenticated user's bookmarks.
     * @param Course $course
     * @return never
     * @throws ForbiddenHttpException
     */
    public function unbookmark(Course $course): never
    {
        $user = App::$auth->getUser();

        if (!$user->getBookmarkedCourses()->contains($course)) {
            throw new ForbiddenHttpException('Vous ne pouvez pas supprimer ce cours de vos favoris car il n\'est pas dans vos favoris.');
        }

        $user->removeBookmarkedCourse($course);
        UserService::Update($user);

        if (isset(getAllInputs()['redirect']) &&
            getAllInputs()['redirect'] !== '') {
            redirect(unescape(getAllInputs()['redirect']));
        }
        else {
            redirect(url('course.show', ['courseId' => $course->getId()]));
        }
    }

    /**
     * @return never
     */
    public function create(): string
    {
        if (!App::$auth->can(Action::Create, new Course())) {
            throw new ForbiddenHttpException('Vous n\'avez pas la permission de créer un cours.');
        }

        $categories = CourseCategoryService::FindAll();

        return App::$templateEngine->run(
            'course.create',
            [
                'categories' => $categories,
            ]
        );
    }

    /**
     * Create and store a new course.
     * @return string
     * @throws ForbiddenHttpException
     * @throws ORMException
     */
    public function store(): string
    {
        if (!App::$auth->can(Action::Create, new Course())) {
            throw new ForbiddenHttpException('Vous n\'avez pas la permission de créer un cours.');
        }

        $inputs = getAllInputs();
        $inputs = array_map('trim', $inputs); // Trim all inputs


        $rules = [
            'titre' => ['required', 'string', 'lenmin:5', 'lenmax:150'],
            'description' => ['required', 'string', 'lenmin:5', 'lenmax:250'],
            'categorie' => ['required', 'integer', 'exists:' . CourseCategory::class],
        ];

        $validator = new Validator($inputs, $rules, App::$db);

        if (!$validator->isValid()) {
            // The inputs are not valid
            flashInputs();

            App::$session->setFlash(ISession::ERROR_KEY, $validator->getErrors());
            return App::$templateEngine->run('course.create', ['categories' => CourseCategoryService::FindAll(),]);
        }

        $file = App::$request->getInputHandler()->file('createinputfile');
        if ($file->hasError() || !in_array(strtolower($file->getMime()), App::$config->get('models.course.bannerAllowedMimeTypes', []))) {
            // The image is not valid or has an invalid format
            flashInputs();

            App::$session->setFlash(ISession::ERROR_KEY, ['Image' => "Le fichier n'est pas une image valide avec un format valide"]);
            return App::$templateEngine->run('course.create', ['categories' => CourseCategoryService::FindAll(),]);
        }

        $media = (new Media())
            ->setName($file->getFilename())
            ->setMimeType($file->getMime())
            ->setFilename(uniqid(more_entropy: true) . '.' . $file->getExtension());

        if (!$file->move(self::COURSE_BANNER_IMG_PATH . '/' . $media->getFilename())) {
            // The image can't be saved as a file on the server
            flashInputs();

            App::$session->setFlash(ISession::ERROR_KEY, ['Image' => "Une erreur est survenue lors de l'upload de l'image"]);
            return App::$templateEngine->run('course.create', ['categories' => CourseCategoryService::FindAll(),]);
        }

        // All is good, we can persist the course and the media in the database
        $course = (new Course())
            ->setTitle($inputs['titre'])
            ->setDescription($inputs['description'])
            ->setCategory(CourseCategoryService::Find((int)$inputs['categorie']))
            ->setBanner($media)
            ->setOwner(App::$auth->getUser())
            ->setVisibility(CourseVisibility::Draft);

        CourseService::Create($course);

        redirect(url('course.edit', ['courseId' => $course->getId()]));
    }

    /**
     * @return never
     */
    public function edit(Course $course): string
    {
        if (!App::$auth->can(Action::Update, $course)) {
            throw new ForbiddenHttpException('Vous n\'avez pas la permission de modifier ce cours.');
        }

        $categories = CourseCategoryService::FindAll();
        $enrollments = $course->getEnrollments();

        return App::$templateEngine->run(
            'course.config',
            [
                'course' => $course,
                'categories' => $categories,
                'enrollments' => $enrollments,
            ]
        );
    }

    /**
     * @param Course $course
     * @return void
     */
    public function update(Course $course): string
    {
        if (!App::$auth->can(Action::Update, $course)) {
            throw new ForbiddenHttpException('Vous n\'avez pas la permission de modifier ce cours.');
        }

        $inputs = getAllInputs();

        $rules = [
            'titre' => ['string', 'lenmin:5', 'lenmax:150'],
            'description' => ['string', 'lenmin:5', 'lenmax:250'],
            'categorie' => ['integer', 'exists:' . CourseCategory::class],
            'visibilite' => ['integer', 'enum:' . CourseVisibility::class],
        ];

        $validator = new Validator($inputs, $rules, App::$db);

        if (!$validator->isValid()) {
            // The inputs are not valid
            flashInputs();

            App::$session->setFlash(ISession::ERROR_KEY, $validator->getErrors());
            return App::$templateEngine->run('course.config', ['course' => $course, 'categories' => CourseCategoryService::FindAll(),]);
        }

        if (isset($inputs['titre'])) {
            $course->setTitle($inputs['titre']);
        }

        if (isset($inputs['description'])) {
            $course->setDescription($inputs['description']);
        }

        if (isset($inputs['categorie'])) {
            $course->setCategory(CourseCategoryService::Find((int)$inputs['categorie']));
        }

        // Check if the image need to be replaced
        $file = App::$request->getInputHandler()->file('createinputfile');
        if ($file->getSize() > 0) {
            if ($file->hasError() || !in_array(strtolower($file->getMime()), App::$config->get('models.course.bannerAllowedMimeTypes', []))) {
                // The image is not valid or has an invalid format
                flashInputs();

                App::$session->setFlash(ISession::ERROR_KEY, ['Image' => "Le fichier n'est pas une image valide avec un format valide"]);
                return App::$templateEngine->run('course.config', ['course' => $course, 'categories' => CourseCategoryService::FindAll(),]);
            }

            $media = (new Media())
                ->setName($file->getFilename())
                ->setMimeType($file->getMime())
                ->setFilename(uniqid(more_entropy: true) . '.' . $file->getExtension());

            if (!$file->move(self::COURSE_BANNER_IMG_PATH . '/' . $media->getFilename())) {
                // The image can't be saved as a file on the server
                flashInputs();

                App::$session->setFlash(ISession::ERROR_KEY, ['Image' => "Une erreur est survenue lors de l'upload de l'image"]);
                return App::$templateEngine->run('course.config', ['course' => $course, 'categories' => CourseCategoryService::FindAll(),]);
            }

            $oldMedia = $course->getBanner();

            // The new image is valid, we can delete the old one
            unlink(self::COURSE_BANNER_IMG_PATH . '/' . $oldMedia->getFilename());

            $course->setBanner($media);

            MediaService::Delete($oldMedia, false);
        }

        // Change the visibility of the course
        if (isset($inputs['visibilite'])) {
            $newVisibility = CourseVisibility::from($inputs['visibilite']);
            if ($newVisibility === CourseVisibility::Public && $course->getChapters()->count() < 1) {
                flashInputs();

                App::$session->setFlash(ISession::ERROR_KEY, ['visibilite' => "Vous ne pouvez pas rendre un cours public s'il n'a pas de chapitre"]);
                return App::$templateEngine->run('course.config', ['course' => $course, 'categories' => CourseCategoryService::FindAll(),]);
            }

            if ($newVisibility === CourseVisibility::Draft && $course->getEnrollments()->count() > 0) {
                flashInputs();

                App::$session->setFlash(ISession::ERROR_KEY, ['visibilite' => "Vous ne pouvez pas rendre un cours 'brouillon s'il a des inscriptions"]);
                return App::$templateEngine->run('course.config', ['course' => $course, 'categories' => CourseCategoryService::FindAll(),]);
            }

            $course->setVisibility($newVisibility);
        }


        CourseService::Update($course);

        redirect(url('course.edit', ['courseId' => $course->getId()]));
    }

    /**
     * @param Course $course
     * @return never
     */
    public function destroy(Course $course): string
    {
        if (!App::$auth->can(Action::Delete, $course)) {
            throw new ForbiddenHttpException('Vous n\'avez pas la permission de supprimer ce cours.');
        }

        if ($course->getEnrollments()->count() > 0) {
            App::$session->setFlash(ISession::ERROR_KEY, ['course' => 'Vous ne pouvez pas supprimer un cours qui a des inscriptions']);
            return App::$templateEngine->run('course.config', ['course' => $course, 'categories' => CourseCategoryService::FindAll(),]);
        }

        // Delete the banner
        $banner = $course->getBanner();
        CourseService::Delete($course, false);
        unlink(self::COURSE_BANNER_IMG_PATH . '/' . $banner->getFilename());

        // Delete the chapter media
        $chapters = $course->getChapters();
        foreach ($chapters as $chapter) {
            unlink(ChapterController::CHAPTER_MEDIA_PATH . '/' . $chapter->getMedia()->getFilename());
        }

        MediaService::Delete($banner); // Remove chapter by cascade

        redirect(url('dashboard.index'));
    }
}
