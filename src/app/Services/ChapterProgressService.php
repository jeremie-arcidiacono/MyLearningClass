<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the ChapterProgress model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\Models\ChapterProgress;

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
     * @param int $id
     * @return ChapterProgress|null
     */
    public static function Find(int $id): ?ChapterProgress
    {
        /** @var ?ChapterProgress $chapterProgress */
        $chapterProgress = parent::FindGeneric($id);
        return $chapterProgress;
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
