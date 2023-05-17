<?php
declare(strict_types=1);
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a controller used to render some of the basic pages of the dashboard.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Controllers;

use App\App;
use App\Enums\Action;
use App\Enums\ChapterProgressStatus;
use App\Enums\CourseVisibility;
use App\Exceptions\ForbiddenHttpException;
use App\Models\Course;
use App\Services\ChapterProgressService;

/**
 * Render some of the basic pages of the dashboard.
 */
class DashboardController
{
    /**
     * Display the courses where the user is enrolled in the dashboard.
     * @return string
     * @throws \Exception
     */
    public function enrolledCourse(): string
    {
        $enrollments = App::$auth->getUser()->getEnrollments();
        $enrolledCourses = [];
        $completedCourses = [];
        foreach ($enrollments as $enrollment) {
            $course = $enrollment->getCourse();

            $chaptersProgress = ChapterProgressService::FindByUserAndCourse(App::$auth->getUser(), $course);

            $isFinished = true;
            foreach ($chaptersProgress as $chapterProgress) {
                if ($chapterProgress->getStatus() !== ChapterProgressStatus::Done) {
                    $isFinished = false;
                    break;
                }
            }

            if ($isFinished) {
                $completedCourses[] = $course;
            }
            else {
                $enrolledCourses[] = $course;
            }
        }

        return App::$templateEngine->run('dashboard.enrolled-course', [
            'enrolledCourses' => $enrolledCourses,
            'completedCourses' => $completedCourses,
        ]);
    }

    /**
     * Display the courses bookmarked by the user in the dashboard.
     * @return string
     * @throws \Exception
     */
    public function bookmarkedCourse(): string
    {
        $bookmarkedCourses = App::$auth->getUser()->getBookmarkedCourses();

        // Remove the bookmarked courses that are not published
        /**
         * @var int $key
         * @var Course $course
         */
        foreach ($bookmarkedCourses as $key => $course) {
            if ($course->getVisibility() !== CourseVisibility::Public) {
                $bookmarkedCourses->remove($key);
            }
        }

        return App::$templateEngine->run(
            'dashboard.bookmarked-course',
            [
                'bookmarkedCourses' => $bookmarkedCourses,
            ]
        );
    }

    /**
     * Display the courses created by the user in the dashboard.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function createdCourse(): string
    {
        if (!App::$auth->can(Action::Create, new Course())) {
            throw new ForbiddenHttpException('Vous n\'avez pas la permission de créer un cours.');
        }

        $createdCourses = App::$auth->getUser()->getCreatedCourses();

        return App::$templateEngine->run(
            'dashboard.created-course',
            [
                'createdCourses' => $createdCourses,
            ]
        );
    }
}
