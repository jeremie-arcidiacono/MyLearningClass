<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This enum is used to define the level of visibility of a course.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Enums;

/**
 *  Used to define the level of visibility of a course.
 */
enum CourseVisibility: string
{
    case Draft = '1';
    case Public = '2';
    case Private = '3';

    /**
     * Returns the label of the enum. This label is used to display the enum to the end user.
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            CourseVisibility::Draft => 'Brouillon',
            CourseVisibility::Public => 'Public',
            CourseVisibility::Private => 'Privé',
        };
    }
}
