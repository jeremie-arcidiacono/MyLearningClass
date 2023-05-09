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
use App\Models\CourseCategory;
use App\Paginator;
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
     * Get the number of courses publicly available in the site.
     * @return int
     */
    public static function getNbCoursesAvailable(): int
    {
        try {
            $qb = App::$db->getRepository(static::getModel())->createQueryBuilder('c');
            $qb->select('count(c.id)');
            $qb->where('c.visibility = ' . CourseVisibility::Public->value);
            return $qb->getQuery()->getSingleScalarResult();
        } catch (ORMException $e) {
            return 0;
        }
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

    /**
     * Find courses with pagination (only public courses).
     * @param int $page
     * @param string|null $search
     * @param CourseCategory|null $category
     * @return Paginator
     */
    public static function FindByWithPagination(
        int $page = 1,
        string|null $search = null,
        CourseCategory|null $category = null,
    ): Paginator {
        $qb = App::$db->getRepository(static::getModel())->createQueryBuilder('c');
        $qb->select('c', 'category', 'user')
            ->leftJoin('c.category', 'category')
            ->leftJoin('c.owner', 'user');
        $qb->where('c.visibility = ' . CourseVisibility::Public->value);
        $qb->orderBy('c.updatedAt', 'DESC');
        $qb->addOrderBy('c.id', 'DESC');

        if ($search !== null) {
            $search = addcslashes($search, '%_');
            $qb->andWhere('c.title LIKE :search');
            $qb->setParameter('search', '%' . $search . '%');
        }

        if ($category !== null) {
            $qb->andWhere('c.category = :category');
            $qb->setParameter('category', $category);
        }

        $perPage = App::$config->get('ux.course.index.nbItemsPerPage', 10);

        return new Paginator($qb, $page, $perPage);
    }
}
