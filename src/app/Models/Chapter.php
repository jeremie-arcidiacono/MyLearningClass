<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a Doctrine entity representing a chapter of a course.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Contracts\IModel;
use App\Models\Traits\HasTimestamps;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, HasLifecycleCallbacks, Id, JoinColumn, ManyToOne, OneToOne, Table};

/**
 * Entity representing a chapter of a course.
 */
#[Entity, Table(name: 'CHAPTER')]
#[HasLifecycleCallbacks]
class Chapter implements IModel
{
    use HasTimestamps;

    #[Column(name: 'idChapter', length: 12, options: ['unsigned' => true])]
    #[Id, GeneratedValue]
    private int $id;

    #[Column(length: 200)]
    private string $title;

    #[OneToOne(targetEntity: Media::class, cascade: ['persist', 'remove'])]
    #[JoinColumn(name: 'mediaVideo', referencedColumnName: 'filename')]
    private ?Media $video;

    #[OneToOne(targetEntity: Media::class, cascade: ['persist', 'remove'])]
    #[JoinColumn(name: 'mediaRessource', referencedColumnName: 'filename')]
    private ?Media $ressource;

    #[ManyToOne(targetEntity: Course::class, inversedBy: 'chapters')]
    #[JoinColumn(name: 'idCourse', referencedColumnName: 'idCourse')]
    private Course $course;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Chapter
     */
    public function setTitle(string $title): Chapter
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Media|null
     */
    public function getVideo(): ?Media
    {
        return $this->video;
    }

    /**
     * @param Media|null $video
     * @return Chapter
     */
    public function setVideo(?Media $video): Chapter
    {
        $this->video = $video;
        return $this;
    }

    /**
     * @return Media|null
     */
    public function getRessource(): ?Media
    {
        return $this->ressource;
    }

    /**
     * @param Media|null $ressource
     * @return Chapter
     */
    public function setRessource(?Media $ressource): Chapter
    {
        $this->ressource = $ressource;
        return $this;
    }


    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @param Course $course
     * @return Chapter
     */
    public function setCourse(Course $course): Chapter
    {
        $this->course = $course;
        return $this;
    }
}
