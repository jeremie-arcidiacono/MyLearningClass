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
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, HasLifecycleCallbacks, Id, JoinColumn, ManyToOne, Table};

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

    #[Column(length: 30)]
    private ?string $videoFilename;

    #[Column(length: 100)]
    private ?string $videoName;

    #[Column(length: 6, options: ['unsigned' => true])]
    private ?int $videoDuration;

    #[Column(length: 30)]
    private ?string $ressourceFilename;

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
     * @return string|null
     */
    public function getVideoFilename(): ?string
    {
        return $this->videoFilename;
    }

    /**
     * @param string $videoFilename
     * @return Chapter
     */
    public function setVideoFilename(string $videoFilename): Chapter
    {
        $this->videoFilename = $videoFilename;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVideoName(): ?string
    {
        return $this->videoName;
    }

    /**
     * @param string $videoName
     * @return Chapter
     */
    public function setVideoName(string $videoName): Chapter
    {
        $this->videoName = $videoName;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getVideoDuration(): ?int
    {
        return $this->videoDuration;
    }

    /**
     * @param int $videoDuration
     * @return Chapter
     */
    public function setVideoDuration(int $videoDuration): Chapter
    {
        $this->videoDuration = $videoDuration;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRessourceFilename(): ?string
    {
        return $this->ressourceFilename;
    }

    /**
     * @param string $ressourceFilename
     * @return Chapter
     */
    public function setRessourceFilename(string $ressourceFilename): Chapter
    {
        $this->ressourceFilename = $ressourceFilename;
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
