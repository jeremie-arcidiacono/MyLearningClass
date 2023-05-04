<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    January 2023
 * Description :    This config file define session parameters.
 ** * * * * * * * * * * * * * * * * * * * * * * */

use App\App;

return [

    /*
     *---------------------------------------------------------------
     * Session driver
     *---------------------------------------------------------------
     * The driver that will be used to store session data.
     * Supported:
     *  - file : default session handler of PHP, store session in a file in the storage folder
     *  - mysql : store session in the same database of the application
     *  - hybrid : store session data in database, but use default session handler of PHP to store temporary 'flash' data
     */
    'driver' => $_ENV['SESSION_DRIVER'] ?? 'file',

    /*
     *---------------------------------------------------------------
     * Session lifetime
     *---------------------------------------------------------------
     * The number of seconds a session will be considered valid.
     * This is used by the database driver to manage garbage collection.
     */
    'lifetime' => 60 * 60 * 24 * 7, // 7 days

    /*
     *---------------------------------------------------------------
     * Session storage path
     *---------------------------------------------------------------
     * The name of the folder where session files will be stored.
     * Only used if driver is 'file' or 'hybrid'.
     */
    'path' => STORAGE_PATH . '/app/sessions',

    /*
     *---------------------------------------------------------------
     * Session cookie parameters
     *---------------------------------------------------------------
     * The parameters of the session cookie.
     * The name is determined by the name of the application.
     */
    'cookie' => [
        'name' => str_replace([' ', '-', "'"], '_', $_ENV['APP_NAME']) . '_sessid',
        'domain' => $_ENV['APP_DOMAIN'] ?? 'localhost',
        'path' => '/',
        'secure' => !App::isDevMode(),
        'httponly' => true,
        'samesite' => 'Lax',
    ],
];
