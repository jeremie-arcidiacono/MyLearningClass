<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    February 2023
 * Description :    This middleware should be used for every route that requires the user to be a guest (not logged in).
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Middleware;

use App\App;
use App\Exceptions\UserMustBeGuestHttpException;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

/**
 * Ensure that the user is a guest.
 * If the user is logged in, throw an exception.
 */
class GuestMiddleware implements IMiddleware
{

    /**
     * @inheritDoc
     * @throws UserMustBeGuestHttpException
     */
    public function handle(Request $request): void
    {
        if (App::$auth->getUser() !== null) {
            throw new UserMustBeGuestHttpException();
        }
    }
}
