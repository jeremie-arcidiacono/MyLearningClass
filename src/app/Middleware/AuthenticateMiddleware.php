<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    February 2023
 * Description :    This middleware should be used for every route that requires the user to be logged in.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Middleware;

use App\App;
use App\Exceptions\UnauthorizedHttpException;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;


/**
 * Ensure that the user is logged in.
 * If not, throw an exception.
 */
class AuthenticateMiddleware implements IMiddleware
{
    /**
     * @inheritDoc
     * @throws UnauthorizedHttpException
     */
    public function handle(Request $request): void
    {
        if (App::$auth->getUser() === null) {
            throw new UnauthorizedHttpException();
        }
    }

}
