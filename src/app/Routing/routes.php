<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This file contains all the routes of the application.
 *                  All routes should be defined in a group with the exceptionHandler of the app (App\Exceptions\ExceptionHandler).
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

use App\Controllers\AuthController;
use App\Middleware\AuthenticateMiddleware;
use App\Routing\Router;

Router::group(['exceptionHandler' => App\Exceptions\ExceptionHandler::class, 'mergeExceptionHandlers' => false], function () {
    // Routes for Authentification
    Router::group(['middleware' => App\Middleware\GuestMiddleware::class], function () {
        Router::get('/connexion', [AuthController::class, 'login_view'])->name('auth.login_view');
        Router::post('/connexion', [AuthController::class, 'login'])->name('auth.login');
        Router::get('/inscription', [AuthController::class, 'register_view'])->name('auth.register_view');
        Router::post('/inscription', [AuthController::class, 'register'])->name('auth.register');
    });
    Router::post('/deconnexion', [AuthController::class, 'destroy'])->addMiddleware(AuthenticateMiddleware::class)->name('auth.logout');


    Router::get('/', [App\Controllers\HomeController::class, 'index'])->name('home');

    Router::get('/cours', [App\Controllers\CourseController::class, 'index'])->name('course.index');
    Router::get('/cours/{courseId}', [App\Controllers\CourseController::class, 'show'])->where(['courseId' => '[0-9]+'])->name('course.show');
    Router::get('/cours/{courseId}/banner', [App\Controllers\CourseController::class, 'renderBannerImg'])->where(['courseId' => '[0-9]+'])->name(
        'course.banner'
    );

    Router::group(['middleware' => App\Middleware\AuthenticateMiddleware::class], function () {
        Router::post('/cours/{courseId}/inscription', [App\Controllers\CourseController::class, 'enroll'])
            ->where(['courseId' => '[0-9]+'])->name('course.enroll');
        Router::delete('/cours/{courseId}/desinscription', [App\Controllers\CourseController::class, 'unenroll'])
            ->where(['courseId' => '[0-9]+'])->name('course.unenroll');

        Router::get('/cours/{courseId}/lesson/{chapter?}', [App\Controllers\ChapterController::class, 'show'])
            ->where(['courseId' => '[0-9]+', 'chapter' => '[0-9]+'])->name('chapter.show');
        Router::get('/cours/{courseId}/chapitres/{chapterId}/video', [App\Controllers\ChapterController::class, 'renderVideo'])
            ->where(['courseId' => '[0-9]+', 'chapterId' => '[0-9]+'])->name('chapter.video');
        Router::get('/cours/{courseId}/chapitres/{chapterId}/ressource', [App\Controllers\ChapterController::class, 'downloadRessource'])
            ->where(['courseId' => '[0-9]+', 'chapterId' => '[0-9]+'])->name('chapter.ressource');
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
