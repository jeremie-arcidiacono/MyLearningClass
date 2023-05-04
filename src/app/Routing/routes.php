<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This file contains all the routes of the application.
 *                  All routes should be defined in a group with the exceptionHandler of the app (App\Exceptions\ExceptionHandler).
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

use App\Routing\Router;

Router::group(['exceptionHandler' => App\Exceptions\ExceptionHandler::class, 'mergeExceptionHandlers' => false], function () {
    Router::get('/', function () {
        echo PHP_EOL . 'Hello world!';
    });
});

// Special route for Clockwork
if (App\App::isDebugEnabled()) {
    Router::get(
        '/__clockwork/{request}',
        function ($request) {
            $clockwork = clock();

            $metadata = $clockwork->getMetadata($request);

            if ($metadata === null) {
                Router::response()->json(['message' => 'Request not found'], 404);
            }

            Router::response()->json($metadata);
        },
        ['defaultParameterRegex' => '[\w\-\/\?\=]+']
    );
}
