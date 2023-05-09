<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the Chapter model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\App;
use App\Models\Chapter;
use App\Models\Course;
use Doctrine\ORM\Exception\ORMException;

/**
 * Database service class for the Chapter model
 */
class ChapterService extends Service
{
    protected static string $model = Chapter::class;

    /**
     * @inheritDoc
     */
    public static function getModel(): string
    {
        return static::$model;
    }

    /**
     * @param int $id
     * @return Chapter|null
     */
    public static function Find(int $id): ?Chapter
    {
        /** @var ?Chapter $chapter */
        $chapter = parent::FindGeneric($id);
        return $chapter;
    }

    /**
     * Find a chapter by its course and its position in the course
     * @param Course $course
     * @param int $position 1 = the first chapter of the course
     * @return void
     */
    public static function FindByCourseAndPosition(Course $course, int $position)
    {
        try {
            return App::$db->getRepository(static::$model)->findOneBy(['course' => $course, 'position' => $position]);
        } catch (ORMException $e) {
            return null;
        }
    }

    /**
     * @param Chapter $chapter
     * @param bool $autoFlush
     * @return Chapter|null
     */
    public static function Create(Chapter $chapter, bool $autoFlush = true): ?Chapter
    {
        /** @var ?Chapter $chapter */
        $chapter = parent::CreateGeneric($chapter, $autoFlush);
        return $chapter;
    }

    /**
     * @param Chapter $chapter
     * @param bool $autoFlush
     * @return Chapter|null
     */
    public static function Update(Chapter $chapter, bool $autoFlush = true): ?Chapter
    {
        /** @var ?Chapter $chapter */
        $chapter = parent::UpdateGeneric($chapter, $autoFlush);
        return $chapter;
    }

    /**
     * @param Chapter $chapter
     * @param bool $autoFlush
     * @return bool
     */
    public static function Delete(Chapter $chapter, bool $autoFlush = true): bool
    {
        return parent::DeleteGeneric($chapter, $autoFlush);
    }
}
