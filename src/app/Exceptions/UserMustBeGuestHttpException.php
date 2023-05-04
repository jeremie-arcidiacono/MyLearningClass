<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This exception is thrown when a user try to access a page that requires to be a guest (not logged in)
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Exceptions;

use Pecee\SimpleRouter\Exceptions\HttpException;
use Throwable;

/**
 * Occurs when a user is logged in and tries to access a page that requires to be a guest (not logged in)
 */
class UserMustBeGuestHttpException extends HttpException
{
    /**
     * @param string $message          [optional] The Exception message to throw.
     * @param int $code                [optional] The Exception code (should always be 403).
     * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct(string $message = 'Cette action est réservée aux utilisateurs non-authentifiés.', int $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
