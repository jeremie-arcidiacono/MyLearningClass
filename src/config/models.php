<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    May 2023
 * Description :    This config file defines some config values related to the models.
 *                  E.g.
 *                      - the type of media that can be uploaded
 ** * * * * * * * * * * * * * * * * * * * * * * */

return [
    'course' => [
        'bannerAllowedMimeTypes' => ['image/jpeg', 'image/png'],
    ],
    'chapter' => [
        'videoAllowedMimeTypes' => ['video/mp4', 'video/webm', 'video/ogg'],
        'ressourceAllowedMimeTypes' => ['application/pdf'],
    ],
];
