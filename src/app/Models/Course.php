<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a Doctrine entity representing a course.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Contracts\IModel;
use App\Enums\CourseVisibility;
use App\Models\Traits\HasAnOwner;
use App\Models\Traits\HasTimestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, HasLifecycleCallbacks, Id, JoinColumn, ManyToMany, ManyToOne, OneToMany, OneToOne, Table};

/**
 * Entity representing a course.
 */
#[Entity, Table(name: 'COURSE')]
#[HasLifecycleCallbacks]
class Course implements IModel
{
    use HasTimestamps;
    use HasAnOwner;

    #[Column(name: 'idCourse', length: 10, options: ['unsigned' => true])]
    #[Id, GeneratedValue]
    private int $id;

    #[Column(length: 150)]
    private string $title;

    #[Column(length: 250)]
    private string $description;

    #[Column(enumType: CourseVisibility::class)]
    private CourseVisibility $visibility;

    #[OneToOne(targetEntity: Media::class, cascade: ['persist', 'remove'])]
    #[JoinColumn(name: 'mediaBanner', referencedColumnName: 'filename')]
    private Media $banner;

    #[ManyToOne(targetEntity: CourseCategory::class, inversedBy: 'courses')]
    #[JoinColumn(name: 'codeCourseCategory', referencedColumnName: 'codeCourseCategory')]
    private CourseCategory $category;

    #[OneToMany(mappedBy: 'course', targetEntity: Chapter::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    private Collection $chapters;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'createdCourses')]
    #[JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    private User $owner;

    #[OneToMany(mappedBy: 'course', targetEntity: CourseEnrollment::class, cascade: ['persist', 'remove'])]
    private Collection $enrollments;

    #[ManyToMany(targetEntity: User::class, mappedBy: 'bookmarkedCourses')]
    private Collection $bookmarkedBy;


    public function __construct()
    {
        $this->chapters = new ArrayCollection();
        $this->enrollments = new ArrayCollection();
        $this->bookmarkedBy = new ArrayCollection();
    }

    /**
     * Get the ID of the model.
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
     * @return $this
     */
    public function setTitle(string $title): Course
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): Course
    {
        $this->description = $description;
        return $this;
    }


    /**
     * @return CourseVisibility
     */
    public function getVisibility(): CourseVisibility
    {
        return $this->visibility;
    }

    /**
     * @param CourseVisibility $visibility
     * @return Course
     */
    public function setVisibility(CourseVisibility $visibility): Course
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * @return Media
     */
    public function getBanner(): Media
    {
        return $this->banner;
    }

    /**
     * @param Media $banner
     * @return Course
     */
    public function setBanner(Media $banner): Course
    {
        $this->banner = $banner;
        return $this;
    }


    /**
     * @return CourseCategory
     */
    public function getCategory(): CourseCategory
    {
        return $this->category;
    }

    /**
     * @param CourseCategory $category
     * @return $this
     */
    public function setCategory(CourseCategory $category): Course
    {
        $this->category = $category;
        return $this;
    }


    /**
     * Get the chapters of the course ordered by position.
     * @return ArrayCollection|Collection
     */
    public function getChapters(): ArrayCollection|Collection
    {
        // We don't do the sort in the setter because doctrine doesn't call it when he hydrates the entity
        $iterator = $this->chapters->getIterator();

        // Order by chapter position ASC
        $iterator->uasort(function (Chapter $a, Chapter $b) {
            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        });

        return new ArrayCollection(iterator_to_array($iterator));
    }

    /**
     * @param Chapter $chapter
     * @return $this
     */
    public function addChapter(Chapter $chapter): Course
    {
        $chapter->setCourse($this);
        $this->chapters[] = $chapter;
        return $this;
    }

    /**
     * @param Chapter $chapter
     * @return $this
     */
    public function removeChapter(Chapter $chapter): Course
    {
        $this->chapters->removeElement($chapter);
        return $this;
    }


    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     * @return $this
     */
    public function setOwner(User $owner): Course
    {
        $owner->addCreatedCourse($this);
        $this->owner = $owner;
        return $this;
    }


    /**
     * @return ArrayCollection|Collection
     */
    public function getEnrollments(): ArrayCollection|Collection
    {
        return $this->enrollments;
    }

    /**
     * @param CourseEnrollment $enrollment
     * @return $this
     */
    public function addEnrollment(CourseEnrollment $enrollment): Course
    {
        $enrollment->setCourse($this);
        $this->enrollments[] = $enrollment;
        return $this;
    }

    /**
     * @param CourseEnrollment $enrollment
     * @return $this
     */
    public function removeEnrollment(CourseEnrollment $enrollment): Course
    {
        $this->enrollments->removeElement($enrollment);
        return $this;
    }


    /**
     * @return ArrayCollection|Collection
     */
    public function getBookmarkedBy(): ArrayCollection|Collection
    {
        return $this->bookmarkedBy;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addBookmarkedBy(User $user): Course
    {
        $this->bookmarkedBy[] = $user;
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeBookmarkedBy(User $user): Course
    {
        $this->bookmarkedBy->removeElement($user);
        return $this;
    }
}
