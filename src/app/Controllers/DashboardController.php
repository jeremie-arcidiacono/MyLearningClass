<?php
declare(strict_types=1);
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Controllers;

use App\App;
use App\Enums\ChapterProgressStatus;
use App\Enums\CourseVisibility;
use App\Exceptions\ForbiddenHttpException;
use App\Models\Course;
use App\Services\ChapterProgressService;

class DashboardController
{
    public function enrolledCourse()
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

    public function bookmarkedCourse()
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

    public function createdCourse()
    {
        if (!App::$auth->can(\App\Enums\Action::Create, new \App\Models\Course())) {
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
