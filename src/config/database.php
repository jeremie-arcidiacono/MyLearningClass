<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    January 2023
 * Description :    This config file defines the database connection parameters.
 ** * * * * * * * * * * * * * * * * * * * * * * */

return [
    /*
     *---------------------------------------------------------------
     * Database connection driver
     *---------------------------------------------------------------
     * The driver that Doctrine will use to connect to the database.
     * Supported: pdo_mysql, mysqli
     */
    'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',

    /*
     *---------------------------------------------------------------
     * Database connection host
     *---------------------------------------------------------------
     */
    'host' => $_ENV['DB_HOST'],

    /*
     *---------------------------------------------------------------
     * Database connection port
     *---------------------------------------------------------------
     */
    'user' => $_ENV['DB_USER'],

    /*
     *---------------------------------------------------------------
     * Database connection password
     *---------------------------------------------------------------
     */
    'password' => $_ENV['DB_PASSWORD'],

    /*
     *---------------------------------------------------------------
     * Database connection name
     *---------------------------------------------------------------
     */
    'dbname' => $_ENV['DB_NAME'],
];
