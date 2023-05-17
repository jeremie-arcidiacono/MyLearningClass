<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This class is used by the router (Pecee\SimpleRouter) to handle exceptions.
 *                  It should be used to handle all known exceptions on all routes.
 *
 *                  Warning : This class is not used to handle execution before the routing process has started.
 *                  To do so, you need to catch the exception in the index.php file and manually pass it to this class.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Exceptions;

use App\App;
use Exception;
use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\Http\Request;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\Handlers\IExceptionHandler;

/**
 * Used by the router (Pecee\SimpleRouter) to handle exceptions.
 */
class ExceptionHandler implements IExceptionHandler
{

    /**
     * Handle all known exceptions.
     * When an unknown exception is thrown, there are two cases:
     *  - If debug mode is enabled, the exception is thrown again
     *  - If debug mode is disabled, a generic error page is displayed
     * @param Request $request
     * @param Exception $error
     * @return never The script ends here
     * @throws Exception Throws the exception again if debug mode is enabled
     */
    public function handleError(Request $request, Exception $error): never
    {
        // Handle known exceptions
        if ($error instanceof NotFoundHttpException) {
            // Page not found
            http_response_code(404);
            echo App::$templateEngine->run('errors.404');
            exit();
        }
        elseif ($error instanceof EntityNotFoundHttpException) {
            // Id given in URL does not correspond to an existing entity in the database
            http_response_code(404);
            echo App::$templateEngine->run('errors.404', [
                'message' => $error->getMessage(),
            ]);
            exit();
        }
        elseif ($error instanceof UnauthorizedHttpException) {
            // User is not logged in
            redirect(url('auth.login_view'));
        }
        elseif ($error instanceof ForbiddenHttpException) {
            // User is not allowed to access this page or to perform this action (for permission reasons)
            http_response_code(403);
            echo App::$templateEngine->run('errors.403', [
                'message' => $error->getMessage(),
            ]);
            exit();
        }
        elseif ($error instanceof BadRequestHttpException) {
            // User is not allowed to access this page or to perform this action (for business logic reasons)
            http_response_code($error->getCode() ?: 400);
            echo App::$templateEngine->run('errors.400', [
                'message' => $error->getMessage(),
            ]);
            exit();
        }
        elseif ($error instanceof UserMustBeGuestHttpException) {
            // User must be a guest to access this page (not logged in)
            redirect(url('home'));
        }
        elseif ($error instanceof TokenMismatchException) {
            // CSRF token mismatch
            http_response_code(403);
            echo 'CSRF token mismatch';
            exit();
        }

        // Handle unknown exceptions
        error_log($error->getFile() . ' : ' . $error->getLine() . ' => ' . $error->getMessage() . PHP_EOL . $error->getTraceAsString());
        http_response_code($error->getCode() ?: 500);

        if (App::isDebugEnabled()) {
            App::$clockwork->requestProcessed();
            throw $error;
        }
        else {
            echo App::$templateEngine->run('errors.500', [
                'error' => $error,
            ]);
            exit();
        }
    }
}
