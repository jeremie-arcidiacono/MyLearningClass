<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the User model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\App;
use App\Enums\ChapterProgressStatus;
use App\Enums\CourseVisibility;
use App\Models\Course;
use App\Models\User;
use App\Paginator;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * Database service class for the User model
 */
class UserService extends Service
{
    protected static string $model = User::class;

    /**
     * @inheritDoc
     */
    public static function getModel(): string
    {
        return static::$model;
    }

    /**
     * Find a user by its email
     * @param string $email
     * @return User|null
     */
    public static function FindByEmail(string $email): ?User
    {
        try {
            return App::$db->getRepository(static::$model)->findOneBy(['email' => $email]);
        } catch (ORMException $e) {
            return null;
        }
    }

    /**
     * @param int $id
     * @return User|null
     */
    public static function Find(int $id): ?User
    {
        /** @var ?User $user */
        $user = parent::FindGeneric($id);
        return $user;
    }

    /**
     * @param User $user
     * @param bool $autoFlush
     * @return User|null
     */
    public static function Create(User $user, bool $autoFlush = true): ?User
    {
        /** @var ?User $user */
        $user = parent::CreateGeneric($user, $autoFlush);
        return $user;
    }

    /**
     * @param User $user
     * @param bool $autoFlush
     * @return User|null
     */
    public static function Update(User $user, bool $autoFlush = true): ?User
    {
        /** @var ?User $user */
        $user = parent::UpdateGeneric($user, $autoFlush);
        return $user;
    }

    /**
     * @param User $user
     * @param bool $autoFlush
     * @return bool
     */
    public static function Delete(User $user, bool $autoFlush = true): bool
    {
        return parent::DeleteGeneric($user, $autoFlush);
    }

    public static function FindByWithPagination(int $page, string|null $search): Paginator
    {
        $qb = App::$db->getRepository(static::getModel())->createQueryBuilder('u');

        $qb->select('u', 'r', 'cp', 'cc')
            ->leftJoin('u.role', 'r')
            ->leftJoin('u.chapterProgresses', 'cp') // Eager loading (useful for the count of in progress courses on user.index page)
            ->leftJoin('u.createdCourses', 'cc') // Eager loading (useful for the count of public courses on user.index page)
            ->orderBy('u.updatedAt', 'DESC')
            ->addOrderBy('u.id', 'DESC');

        if ($search) {
            $qb->where('u.email LIKE :search')
                ->orWhere('u.firstname LIKE :search')
                ->orWhere('u.lastname LIKE :search')
                ->setParameter('search', '%' . addcslashes($search, '%_') . '%');
        }

        $perPage = App::$config->get('ux.user.index.nbItemsPerPage', 10);

        return new Paginator($qb, $page, $perPage);
    }

    /**
     * Return true if the user has at least one course in progress or finished
     * @param User $user
     * @return bool
     * @throws NotSupported
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public static function UserHasCourseInProgressOrDone(User $user): bool
    {
        $chapterProgress = $user->getChapterProgresses();

        foreach ($chapterProgress as $chapter) {
            if ($chapter->getStatus() === ChapterProgressStatus::InProgress || $chapter->getStatus() === ChapterProgressStatus::Done) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return true if the user (teacher) has at least one public course
     * @param User $user
     * @return bool
     */
    public static function UserHasPublicCourse(User $user): bool
    {
        $courses = $user->getCreatedCourses();

        foreach ($courses as $course) {
            if ($course->getVisibility() === CourseVisibility::Public) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user (who has created courses) has at least one student in one of his courses
     * @param User $user
     * @return bool
     */
    public static function TeacherHasStudent(User $user): bool
    {
        $courses = $user->getCreatedCourses();

        /** @var Course $course */
        foreach ($courses as $course) {
            if ($course->getEnrollments()->count() > 0) {
                return true;
            }
        }

        return false;
    }
}
