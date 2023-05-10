<?php
declare(strict_types=1);
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Controllers;

use App\App;
use App\Enums\CourseVisibility;
use App\Models\Course;

class DashboardController
{

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
                unset($bookmarkedCourses[$key]);
            }
        }

        return App::$templateEngine->run(
            'dashboard.bookmarked-course',
            [
                'bookmarkedCourses' => $bookmarkedCourses,
            ]
        );
    }
}
