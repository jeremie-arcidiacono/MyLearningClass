<?php
declare(strict_types=1);


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    May 2023
 * Description :    This class is a Doctrine entity representing a course enrollment.
 *                  This is an association class between User and Course with additional data.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Contracts\IModel;
use App\Models\Traits\HasAnOwner;
use App\Models\Traits\HasCreatedAt;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Entity representing a course enrollment.
 * This is an association class between User and Course with additional data.
 */
#[Entity, Table(name: 'COURSE_ENROLLMENT')]
#[HasLifecycleCallbacks]
class CourseEnrollment implements IModel
{
    use HasAnOwner;

    // The student is the owner of the enrollment (this is important for the permission system)

    use HasCreatedAt;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'enrollments')]
    #[JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    #[Id]
    private User $student;

    #[ManyToOne(targetEntity: Course::class, inversedBy: 'enrollments')]
    #[JoinColumn(name: 'idCourse', referencedColumnName: 'idCourse')]
    #[Id]
    private Course $course;

    /**
     * @return User
     */
    public function getStudent(): User
    {
        return $this->student;
    }

    /**
     * @param User $student
     * @return CourseEnrollment
     */
    public function setStudent(User $student): CourseEnrollment
    {
        $this->student = $student;
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
     * @return CourseEnrollment
     */
    public function setCourse(Course $course): CourseEnrollment
    {
        $this->course = $course;
        return $this;
    }

    /**
     * Get the owner of the model (alias of getStudent method).
     * @return User
     */
    public function getOwner(): User
    {
        return $this->getStudent();
    }

    /**
     * Set the owner of the model (alias of setStudent method).
     * @param User $owner
     * @return CourseEnrollment
     */
    public function setOwner(User $owner): CourseEnrollment
    {
        return $this->setStudent($owner);
    }
}
