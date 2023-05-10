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
use App\Services\ChapterProgressService;
use App\Services\CourseCategoryService;
use App\Services\CourseEnrollmentService;
use App\Services\CourseService;
use App\Services\Service;
use App\Services\UserService;
use App\Validator;

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
                'nbCourseAvailable' => CourseService::getNbCoursesAvailable(),
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
            ->header('Cache-Control: private, max-age=86400')
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
        elseif ($course->getVisibility() !== CourseVisibility::Public) {
            // The course is not public, so the user must be enrolled or be a teacher.
            if (!(App::$auth->getUser()->getId() === $course->getOwner()->getId()) &&
                !(CourseEnrollmentService::isEnrolled(App::$auth->getUser(), $course))) {
                return false;
            }
        }
        return true;
    }

    /**
     * Enroll the authenticated user to the course.
     * @param Course $course
     * @return never
     */
    public function enroll(Course $course): never
    {
        $user = App::$auth->getUser();

        if (CourseEnrollmentService::isEnrolled($user, $course)) {
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
}
