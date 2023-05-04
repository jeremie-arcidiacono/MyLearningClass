<?php
declare(strict_types=1);


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    May 2023
 * Description :    This enum is used to define the progress of a user in a chapter.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Enums;

/**
 *  Used to define the progress of a user in a chapter.
 */
enum ChapterProgressStatus: string
{
    case ToDo = '1';
    case InProgress = '2';
    case Done = '3';

    /**
     * Returns the label of the enum. This label is used to display the enum to the end user.
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            ChapterProgressStatus::ToDo => 'À faire',
            ChapterProgressStatus::InProgress => 'Entamé',
            ChapterProgressStatus::Done => 'Terminé',
        };
    }
}
