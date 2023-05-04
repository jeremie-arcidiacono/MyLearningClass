<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    February 2023
 * Description :    This config file defines the migrations parameters used by Doctrine when running CLI commands.
 *
 * @see https://www.doctrine-project.org/projects/doctrine-migrations/en/3.6/reference/configuration.html
 ** * * * * * * * * * * * * * * * * * * * * * * */

return [
    'table_storage' => [
        'table_name' => 'doctrine_migration_versions',
        'version_column_name' => 'version',
        'version_column_length' => 191,
        'executed_at_column_name' => 'executed_at',
        'execution_time_column_name' => 'execution_time',
    ],

    /*
     *---------------------------------------------------------------
     * Migrations paths
     *--------------------------------------------------------------
     * The paths where the migrations files are stored
     */
    'migrations_paths' => [
        'Migrations' => '../migrations',
    ],

    'all_or_nothing' => true,
    'transactional' => true,
    'check_database_platform' => true,
    'organize_migrations' => 'none',
    'connection' => null,
    'em' => null,
];
