<?php
declare(strict_types=1);


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    May 2023
 * Description :    This class is a Doctrine entity representing a chapter progress.
 *                  After a user enrolled in a course, he can start to take the courses.
 *                  He marks each chapter as 'to do', 'in progress' or 'done', etc. (see ChapterProgressStatus enum).
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Enums\ChapterProgressStatus;
use App\Models\Traits\HasCreatedAt;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 *  Entity representing a chapter progress.
 *  After a user enrolled in a course, he can start to take the courses.
 *  He marks each chapter as 'to do', 'in progress' or 'done', etc. (see ChapterProgressStatus enum).
 */
#[Entity, Table(name: 'CHAPTER_PROGRESS')]
#[HasLifecycleCallbacks]
class ChapterProgress
{
    use HasCreatedAt;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    #[Id]
    private User $student;

    #[ManyToOne(targetEntity: Chapter::class)]
    #[JoinColumn(name: 'idChapter', referencedColumnName: 'idChapter')]
    #[Id]
    private Chapter $chapter;

    #[Column(name: 'status', enumType: ChapterProgressStatus::class)]
    private ChapterProgressStatus $status;

    /**
     * @return User
     */
    public function getStudent(): User
    {
        return $this->student;
    }

    /**
     * @param User $student
     * @return ChapterProgress
     */
    public function setStudent(User $student): ChapterProgress
    {
        $this->student = $student;
        return $this;
    }

    /**
     * @return Chapter
     */
    public function getChapter(): Chapter
    {
        return $this->chapter;
    }

    /**
     * @param Chapter $chapter
     * @return ChapterProgress
     */
    public function setChapter(Chapter $chapter): ChapterProgress
    {
        $this->chapter = $chapter;
        return $this;
    }

    /**
     * @return ChapterProgressStatus
     */
    public function getStatus(): ChapterProgressStatus
    {
        return $this->status;
    }

    /**
     * @param ChapterProgressStatus $status
     * @return ChapterProgress
     */
    public function setStatus(ChapterProgressStatus $status): ChapterProgress
    {
        $this->status = $status;
        return $this;
    }


}
