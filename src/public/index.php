<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    December 2022
 * Description :    This is the entry point of the application.
 *                  All requests are redirected to this file by the .htaccess file.
 *                  Here we initialize the application and run it.
 *                  We also catch any exception that could be thrown in the initialization of the app.
 ** * * * * * * * * * * * * * * * * * * * * * * */

use App\App;

require_once __DIR__ . '/../vendor/autoload.php';

// Define directories paths constants
require_once __DIR__ . '/../config/constants.php';

// This try catch is the last resort to catch any exception that could be thrown in the initialization of the app, when the app cannot use any of its
// services to handle the exception properly (like rendering a view or redirecting the user to an error page)
// The error would typically be a database connection error or a missing .env file
try {
    $app = new App();
} catch (Exception $e) {
    http_response_code(500);

    // Check if the .env file has been loaded despite the exception
    if (isset($_ENV['APP_DEBUG']) &&
        ($_ENV['APP_DEBUG'] == 'true' || $_ENV['APP_DEBUG'] == '1')) {
        // We are in debug mode, so we can display the error message
        echo $e->getMessage();
        exit;
    }

    // We cannot determine if we are in debug mode or not, so we display a generic error message
    echo 'Le site est indisponible pour le moment.';
    exit;
}

$app->run();
