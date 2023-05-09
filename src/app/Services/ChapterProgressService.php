<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the ChapterProgress model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\App;
use App\Models\Chapter;
use App\Models\ChapterProgress;
use App\Models\Course;
use App\Models\User;
use Doctrine\ORM\Exception\ORMException;

/**
 * Database service class for the ChapterProgress model
 */
class ChapterProgressService extends Service
{
    protected static string $model = ChapterProgress::class;

    /**
     * @inheritDoc
     */
    public static function getModel(): string
    {
        return static::$model;
    }

    /**
     * @param User $user
     * @param Chapter $chapter
     * @return ChapterProgress|null
     */
    public static function Find(User $user, Chapter $chapter): ?ChapterProgress
    {
        try {
            return App::$db->getRepository(static::$model)->findOneBy(['student' => $user, 'chapter' => $chapter]);
        } catch (ORMException $e) {
            return null;
        }
    }

    /**
     * Get all chapter progresses of a user on a course
     * @param User $user
     * @param Course $course
     * @return array|null
     */
    public static function FindByUserAndCourse(User $user, Course $course): ?array
    {
        try {
            $qb = App::$db->getRepository(static::getModel())->createQueryBuilder('cp');
            $qb->where('cp.student = :student')->setParameter('student', $user);
            $qb->andWhere('cp.chapter IN (:chapters)')->setParameter('chapters', $course->getChapters());
            return $qb->getQuery()->getResult();
        } catch (ORMException $e) {
            return null;
        }
    }

    /**
     * @param ChapterProgress $chapterProgress
     * @param bool $autoFlush
     * @return ChapterProgress|null
     */
    public static function Create(ChapterProgress $chapterProgress, bool $autoFlush = true): ?ChapterProgress
    {
        /** @var ?ChapterProgress $chapterProgress */
        $chapterProgress = parent::CreateGeneric($chapterProgress, $autoFlush);
        return $chapterProgress;
    }

    /**
     * @param ChapterProgress $chapterProgress
     * @param bool $autoFlush
     * @return ChapterProgress|null
     */
    public static function Update(ChapterProgress $chapterProgress, bool $autoFlush = true): ?ChapterProgress
    {
        /** @var ?ChapterProgress $chapterProgress */
        $chapterProgress = parent::UpdateGeneric($chapterProgress, $autoFlush);
        return $chapterProgress;
    }

    /**
     * @param ChapterProgress $chapterProgress
     * @param bool $autoFlush
     * @return bool
     */
    public static function Delete(ChapterProgress $chapterProgress, bool $autoFlush = true): bool
    {
        return parent::DeleteGeneric($chapterProgress, $autoFlush);
    }
}
