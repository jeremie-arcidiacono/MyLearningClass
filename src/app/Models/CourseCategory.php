<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a Doctrine entity representing a course category.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Contracts\IModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Column, Entity, HasLifecycleCallbacks, Id, OneToMany, Table};

/**
 * Entity representing a course category.
 */
#[Entity, Table(name: 'COURSE_CATEGORY')]
#[HasLifecycleCallbacks]
class CourseCategory implements IModel
{
    #[Column(name: 'codeCourseCategory', length: 4, options: ['unsigned' => true])]
    #[Id]
    private int $id;

    #[Column(length: 30)]
    private string $label;

    #[OneToMany(mappedBy: 'category', targetEntity: Course::class)]
    private Collection $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CourseCategory
     */
    public function setId(int $id): CourseCategory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return CourseCategory
     */
    public function setLabel(string $label): CourseCategory
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    /**
     * @param Course $course
     * @return CourseCategory
     */
    public function addCourse(Course $course): CourseCategory
    {
        $this->courses->add($course);
        return $this;
    }


}
