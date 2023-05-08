<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the Course model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\App;
use App\Enums\CourseVisibility;
use App\Models\Course;
use Doctrine\ORM\Exception\ORMException;

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

    /**
     * Find random courses.
     * @param int $limit The number of courses to find.
     * @return Course[]
     */
    public static function FindRandom(int $limit): array
    {
        try {
            $nbRows = App::$db->getRepository(static::getModel())->createQueryBuilder('c')->select('count(c.id)')->getQuery()->getSingleScalarResult();

            $offset = rand(0, $nbRows - $limit - 1);

            $qb = App::$db->getRepository(static::getModel())->createQueryBuilder('c');
            $qb->select('c', 'category', 'user')
                ->leftJoin('c.category', 'category')
                ->leftJoin('c.owner', 'user');
            $qb->where('c.visibility = ' . CourseVisibility::Public->value);
            $qb->setFirstResult($offset);
            $qb->setMaxResults($limit);
            $result = $qb->getQuery()->getResult();
            
            shuffle($result); // To be sure there is no order
            return $result;
        } catch (ORMException $e) {
            return [];
        }
    }
}
