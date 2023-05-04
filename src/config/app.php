<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    January 2023
 * Description :    This config file is used to store the configuration of common variables of the app.
 ** * * * * * * * * * * * * * * * * * * * * * * */

return [
    /*
    *--------------------------------------------------------------------------
    * Application Name
    *--------------------------------------------------------------------------
    * This value is the name of your application. This value used frequently in the app like in :
    * - the title of the pages and the layout
    * - the name of the session/cookies
    * - the footer of the emails if you use the mailer
    * - etc.
    */
    'name' => $_ENV['APP_NAME'] ?? 'My web app',

    /*
     *--------------------------------------------------------------------------
     * Application Environment
     *--------------------------------------------------------------------------
     * This value determines the "environment" your application is currently.
     * This determines howto configure various services your
     * application utilizes. It impacts the performance of the app.
     * There are 2 possible values :
     * - production
     * - development/dev/local
     */
    'env' => $_ENV['APP_ENV'] ?? 'production',

    /*
     *--------------------------------------------------------------------------
     * Application Debug Mode
     *--------------------------------------------------------------------------
     * When your application is in debug mode, detailed error messages will be shown.
     * If disabled, a simple generic 500 error page is shown to the user.
     * It also determines if the app load some debug tools like Clockwork.
     * There are 2 possible values :
     * - true
     * - false
     */
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),

    /*
     *--------------------------------------------------------------------------
     * Application domain name
     *--------------------------------------------------------------------------
     * This value is the domain name of your application.
     * It is used to specify the domain attribute of the cookies.
     */
    'domain' => $_ENV['APP_DOMAIN'] ?? 'localhost',

    /*
     *--------------------------------------------------------------------------
     * Application Timezone
     *--------------------------------------------------------------------------
     * This value determines the timezone of your application.
     * You can find the list of all the timezones here : https://www.php.net/manual/en/timezones.php
     */
    'timezone' => $_ENV['TIMEZONE'] ?? 'Europe/Zurich',
];
