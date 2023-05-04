<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the CourseEnrollment model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\Models\CourseEnrollment;

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
}
