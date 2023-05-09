<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    May 2023
 * Description :    This config file defines some config values related to the UX of the application.
 *                  E.g.
 *                      - the number of items per page in a paginated list.
 *                      - the number of items in a list of suggestions.
 *                      - the format of a date.
 ** * * * * * * * * * * * * * * * * * * * * * * */

return [
    'home' => [
        'nbRandomCourseDisplayed' => 9,
    ],
    'course' => [
        'index' => [
            'nbItemsPerPage' => 9,
        ],
    ],
];
