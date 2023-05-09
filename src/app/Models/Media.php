<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a Doctrine entity representing a media (video, image, pdf, etc.).
 *                  A media is linked to another entity (course, chapter, etc.) by a one-to-one relationship.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Contracts\IModel;
use Doctrine\ORM\Mapping\{Column, Entity, Id, Table};

/**
 * Entity representing a role (alias for a group of permissions)
 */
#[Entity, Table(name: 'MEDIA')]
class Media implements IModel
{
    #[Column(length: 30)]
    #[Id]
    private string $filename;

    #[Column(length: 100)]
    private string $name;

    #[Column(length: 15)]
    private string $mimeType;

    #[Column(length: 6, options: ['unsigned' => true])]
    private ?int $duration;

    /**
     * The name of the file stored on the server.
     * Used as the primary key.
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * The name of the file stored on the server.
     * Used as the primary key.
     * @param string $filename
     * @return Media
     */
    public function setFilename(string $filename): Media
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * The original name of the video, before renaming to avoid collisions.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * The original name of the video, before renaming to avoid collisions.
     * @param string $name
     * @return Media
     */
    public function setName(string $name): Media
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     * @return Media
     */
    public function setMimeType(string $mimeType): Media
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Only if the media is a video.
     * The duration of the video in seconds.
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * Only if the media is a video.
     * The duration of the video in seconds.
     * @param int|null $duration
     * @return Media
     */
    public function setDuration(?int $duration): Media
    {
        $this->duration = $duration;
        return $this;
    }


}
