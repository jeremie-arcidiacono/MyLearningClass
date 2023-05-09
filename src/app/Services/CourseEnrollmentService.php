<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the CourseEnrollment model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\App;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Doctrine\ORM\Exception\ORMException;

/**
 * Database service class for the CourseEnrollment model
 */
class CourseEnrollmentService extends Service
{
    protected static string $model = CourseEnrollment::class;

    /**
     * @inheritDoc
     */
    public static function getModel(): string
    {
        return static::$model;
    }

    /**
     * @param int $id
     * @return CourseEnrollment|null
     */
    public static function Find(int $id): ?CourseEnrollment
    {
        /** @var ?CourseEnrollment $courseEnrollment */
        $courseEnrollment = parent::FindGeneric($id);
        return $courseEnrollment;
    }

    /**
     * Find a course enrollment by its user and its course
     * @param User $user
     * @param Course $course
     * @return CourseEnrollment|null
     */
    public static function FindByUserAndCourse(User $user, Course $course): ?CourseEnrollment
    {
        try {
            return App::$db->getRepository(static::$model)->findOneBy(['student' => $user, 'course' => $course]);
        } catch (ORMException $e) {
            return null;
        }
    }

    /**
     * @param CourseEnrollment $courseEnrollment
     * @param bool $autoFlush
     * @return CourseEnrollment|null
     */
    public static function Create(CourseEnrollment $courseEnrollment, bool $autoFlush = true): ?CourseEnrollment
    {
        /** @var ?CourseEnrollment $courseEnrollment */
        $courseEnrollment = parent::CreateGeneric($courseEnrollment, $autoFlush);
        return $courseEnrollment;
    }

    /**
     * @param CourseEnrollment $courseEnrollment
     * @param bool $autoFlush
     * @return CourseEnrollment|null
     */
    public static function Update(CourseEnrollment $courseEnrollment, bool $autoFlush = true): ?CourseEnrollment
    {
        /** @var ?CourseEnrollment $courseEnrollment */
        $courseEnrollment = parent::UpdateGeneric($courseEnrollment, $autoFlush);
        return $courseEnrollment;
    }

    /**
     * @param CourseEnrollment $courseEnrollment
     * @param bool $autoFlush
     * @return bool
     */
    public static function Delete(CourseEnrollment $courseEnrollment, bool $autoFlush = true): bool
    {
        return parent::DeleteGeneric($courseEnrollment, $autoFlush);
    }

    /**
     * Get if the user is enrolled in the course
     * @param User $user
     * @param Course $course
     * @return false
     */
    public static function isEnrolled(User $user, Course $course): bool
    {
        $enrollment = $user->getEnrollments();

        foreach ($enrollment as $enroll) {
            if ($enroll->getCourse() == $course) {
                return true;
            }
        }
        return false;
    }
}
