<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a Doctrine entity representing a user
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Contracts\IModel;
use App\Models\Traits\HasTimestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Column,
    Entity,
    GeneratedValue,
    HasLifecycleCallbacks,
    Id,
    InverseJoinColumn,
    JoinColumn,
    JoinTable,
    ManyToMany,
    ManyToOne,
    OneToMany,
    Table};

/**
 * Entity representing a user
 */
#[Entity, Table(name: 'USER')]
#[HasLifecycleCallbacks]
class User implements IModel
{
    use HasTimestamps;

    #[Column(name: 'idUser', length: 10, options: ['unsigned' => true])]
    #[Id, GeneratedValue]
    private int $id;

    #[Column(length: 30)]
    private string $firstname;

    #[Column(length: 30)]
    private string $lastname;

    #[Column(length: 50, unique: true)]
    private string $email;

    #[Column(length: 60)]
    private string $password;


    #[ManyToOne(targetEntity: Role::class)]
    #[JoinColumn(name: 'codeRole', referencedColumnName: 'codeRole')]
    private Role $role;

    #[OneToMany(mappedBy: 'owner', targetEntity: Course::class, cascade: ['persist', 'remove'])]
    private Collection $createdCourses;

    #[OneToMany(mappedBy: 'student', targetEntity: CourseEnrollment::class, cascade: ['persist', 'remove'])]
    private Collection $enrollments;

    #[ManyToMany(targetEntity: Course::class, inversedBy: User::class)]
    #[JoinTable(name: 'COURSE_BOOKMARK')]
    #[JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    #[InverseJoinColumn(name: 'idCourse', referencedColumnName: 'idCourse')]
    private Collection $bookmarkedCourses;

    #[OneToMany(mappedBy: 'student', targetEntity: ChapterProgress::class, cascade: ['persist', 'remove'])]
    private Collection $chapterProgresses;


    public function __construct()
    {
        $this->createdCourses = new ArrayCollection();
        $this->enrollments = new ArrayCollection();
        $this->bookmarkedCourses = new ArrayCollection();
        $this->chapterProgresses = new ArrayCollection();
    }

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
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return User
     */
    public function setLastname(string $lastname): User
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @param Role $role
     * @return User
     */
    public function setRole(Role $role): User
    {
        $this->role = $role;
        return $this;
    }


    /**
     * Get the courses created by the user
     * @return ArrayCollection|Collection
     */
    public function getCreatedCourses(): ArrayCollection|Collection
    {
        return $this->createdCourses;
    }

    /**
     * Add a course to the user's created courses collection
     * @param Course $course
     * @return User
     */
    public function addCreatedCourse(Course $course): User
    {
        $this->createdCourses->add($course);
        return $this;
    }

    /**
     * Remove a course from the user's created courses collection
     * @param Course $course
     * @return User
     */
    public function removeCreatedCourse(Course $course): User
    {
        $this->createdCourses->removeElement($course);
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
     * Enroll the user to a course
     * @param Course $course
     * @return User
     */
    public function createEnrollment(Course $course): User
    {
        $enrollment = new CourseEnrollment();
        $enrollment->setStudent($this);
        $course->addEnrollment($enrollment);
        $this->enrollments->add($enrollment);
        return $this;
    }

    /**
     * @param CourseEnrollment $enrollment
     * @return $this
     */
    public function addEnrollment(CourseEnrollment $enrollment): User
    {
        $enrollment->setStudent($this);
        $this->enrollments->add($enrollment);
        return $this;
    }

    /**
     * @param CourseEnrollment $enrollment
     * @return User
     */
    public function removeEnrollment(CourseEnrollment $enrollment): User
    {
        $this->enrollments->removeElement($enrollment);
        return $this;
    }


    /**
     * @return ArrayCollection|Collection
     */
    public function getBookmarkedCourses(): ArrayCollection|Collection
    {
        return $this->bookmarkedCourses;
    }

    /**
     * @param Course $course
     * @return User
     */
    public function addBookmarkedCourse(Course $course): User
    {
        $course->addBookmarkedBy($this);
        $this->bookmarkedCourses->add($course);
        return $this;
    }

    /**
     * @param Course $course
     * @return User
     */
    public function removeBookmarkedCourse(Course $course): User
    {
        $course->removeBookmarkedBy($this);
        $this->bookmarkedCourses->removeElement($course);
        return $this;
    }


    /**
     * @return ArrayCollection|Collection
     */
    public function getChapterProgresses(): ArrayCollection|Collection
    {
        return $this->chapterProgresses;
    }

    /**
     * @param ChapterProgress $chapterProgress
     * @return User
     */
    public function addChapterProgress(ChapterProgress $chapterProgress): User
    {
        $chapterProgress->setStudent($this);
        $this->chapterProgresses->add($chapterProgress);
        return $this;
    }

    /**
     * @param ChapterProgress $chapterProgress
     * @return $this
     */
    public function removeChapterProgress(ChapterProgress $chapterProgress): User
    {
        $this->chapterProgresses->removeElement($chapterProgress);
        return $this;
    }
}
