<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the Media model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\Models\Media;

/**
 * Database service class for the Media model
 */
class MediaService extends Service
{
    protected static string $model = Media::class;

    /**
     * @inheritDoc
     */
    public static function getModel(): string
    {
        return static::$model;
    }

    /**
     * @param int $id
     * @return Media|null
     */
    public static function Find(int $id): ?Media
    {
        /** @var ?Media $media */
        $media = parent::FindGeneric($id);
        return $media;
    }

    /**
     * @param Media $media
     * @param bool $autoFlush
     * @return Media|null
     */
    public static function Create(Media $media, bool $autoFlush = true): ?Media
    {
        /** @var ?Media $media */
        $media = parent::CreateGeneric($media, $autoFlush);
        return $media;
    }

    /**
     * @param Media $media
     * @param bool $autoFlush
     * @return Media|null
     */
    public static function Update(Media $media, bool $autoFlush = true): ?Media
    {
        /** @var ?Media $media */
        $media = parent::UpdateGeneric($media, $autoFlush);
        return $media;
    }

    /**
     * @param Media $media
     * @param bool $autoFlush
     * @return bool
     */
    public static function Delete(Media $media, bool $autoFlush = true): bool
    {
        return parent::DeleteGeneric($media, $autoFlush);
    }
}
