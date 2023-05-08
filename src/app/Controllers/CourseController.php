<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a controller used to manage the courses.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


namespace App\Controllers;

use App\App;
use App\Enums\CourseVisibility;
use App\Exceptions\ForbiddenHttpException;
use App\Models\Course;

/**
 *  Manage the courses.
 */
class CourseController
{
    public const COURSE_BANNER_IMG_PATH = STORAGE_PATH . '/assets/courses/banners';

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

        $filePath = self::COURSE_BANNER_IMG_PATH . '/' . $course->getImgFilename();

        if (!file_exists($filePath)) {
            throw new \Exception("Erreur interne. L'image n'existe pas.");
        }


        $mime = $course->getImgMimeType();

        App::$response->httpCode(200)
            ->header("Content-Type: $mime")
            ->header('Content-Length:' . filesize($filePath))
            ->header('Cache-Control: private, max-age=86400')
            ->render($filePath);
    }

    /**
     * Check if the current logged user (or a guest) can see the course.
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
                !(App::$auth->getUser()->getEnrollments()->contains($course))) {
                return false;
            }
        }
        return true;
    }
}
