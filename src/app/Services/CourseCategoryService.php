<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the CourseCategory model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\Models\CourseCategory;

/**
 * Database service class for the CourseCategory model
 */
class CourseCategoryService extends Service
{
    protected static string $model = CourseCategory::class;

    /**
     * @inheritDoc
     */
    public static function getModel(): string
    {
        return static::$model;
    }

    /**
     * @param int $id
     * @return CourseCategory|null
     */
    public static function Find(int $id): ?CourseCategory
    {
        /** @var ?CourseCategory $courseCategory */
        $courseCategory = parent::FindGeneric($id);
        return $courseCategory;
    }
}
