<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the Course model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\Models\Course;

/**
 * Database service class for the Course model
 */
class CourseService extends Service
{
    protected static string $model = Course::class;

    /**
     * @inheritDoc
     */
    public static function getModel(): string
    {
        return static::$model;
    }

    /**
     * @param int $id
     * @return Course|null
     */
    public static function Find(int $id): ?Course
    {
        /** @var ?Course $course */
        $course = parent::FindGeneric($id);
        return $course;
    }

    /**
     * @param Course $course
     * @param bool $autoFlush
     * @return Course|null
     */
    public static function Create(Course $course, bool $autoFlush = true): ?Course
    {
        /** @var ?Course $course */
        $course = parent::CreateGeneric($course, $autoFlush);
        return $course;
    }

    /**
     * @param Course $course
     * @param bool $autoFlush
     * @return Course|null
     */
    public static function Update(Course $course, bool $autoFlush = true): ?Course
    {
        /** @var ?Course $course */
        $course = parent::UpdateGeneric($course, $autoFlush);
        return $course;
    }

    /**
     * @param Course $course
     * @param bool $autoFlush
     * @return bool
     */
    public static function Delete(Course $course, bool $autoFlush = true): bool
    {
        return parent::DeleteGeneric($course, $autoFlush);
    }
}
