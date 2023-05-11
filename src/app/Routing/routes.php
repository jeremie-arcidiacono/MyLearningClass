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

    // Only for authenticated users
    Router::group(['middleware' => App\Middleware\AuthenticateMiddleware::class], function () {
        Router::group(['prefix' => '/cours'], function () {
            Router::post('/{courseId}/inscription', [App\Controllers\CourseController::class, 'enroll'])
                ->where(['courseId' => '[0-9]+'])->name('course.enroll');
            Router::delete('/{courseId}/desinscription', [App\Controllers\CourseController::class, 'unenroll'])
                ->where(['courseId' => '[0-9]+'])->name('course.unenroll');

            Router::get('/{courseId}/lesson/{chapter?}', [App\Controllers\ChapterController::class, 'show'])
                ->where(['courseId' => '[0-9]+', 'chapter' => '[0-9]+'])->name('chapter.show');
            Router::get('/{courseId}/chapitres/{chapterId}/video', [App\Controllers\ChapterController::class, 'renderVideo'])
                ->where(['courseId' => '[0-9]+', 'chapterId' => '[0-9]+'])->name('chapter.video');
            Router::get('/{courseId}/chapitres/{chapterId}/ressource', [App\Controllers\ChapterController::class, 'downloadRessource'])
                ->where(['courseId' => '[0-9]+', 'chapterId' => '[0-9]+'])->name('chapter.ressource');
            Router::put('/{courseId}/chapitres/{chapterId}/progression', [App\Controllers\ChapterController::class, 'updateProgression'])
                ->where(['courseId' => '[0-9]+', 'chapterId' => '[0-9]+'])->name('chapter.updateProgression');

            Router::post('/{courseId}/favoris', [App\Controllers\CourseController::class, 'bookmark'])
                ->where(['courseId' => '[0-9]+'])->name('course.bookmark');
            Router::delete('/{courseId}/favoris', [App\Controllers\CourseController::class, 'unbookmark'])
                ->where(['courseId' => '[0-9]+'])->name('course.unbookmark');

            Router::get('/creation', [App\Controllers\CourseController::class, 'create'])->name('course.create');
            Router::post('/creation', [App\Controllers\CourseController::class, 'store'])->name('course.store');
            Router::get('/{courseId}/configuration', [App\Controllers\CourseController::class, 'edit'])
                ->where(['courseId' => '[0-9]+'])->name('course.edit');
            Router::put('/{courseId}/configuration', [App\Controllers\CourseController::class, 'update'])
                ->where(['courseId' => '[0-9]+'])->name('course.update');
            Router::delete('/{courseId}', [App\Controllers\CourseController::class, 'destroy'])
                ->where(['courseId' => '[0-9]+'])->name('course.destroy');
        });

        Router::group(['prefix' => '/dashboard'], function () {
            Router::redirect('/', '/dashboard/inscriptions', 301)->name('dashboard.index');
            Router::get('/inscriptions', [App\Controllers\DashboardController::class, 'enrolledCourse'])->name('dashboard.enrolledCourse');
            Router::get('/favoris', [App\Controllers\DashboardController::class, 'bookmarkedCourse'])->name('dashboard.bookmarkedCourse');
        });
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
