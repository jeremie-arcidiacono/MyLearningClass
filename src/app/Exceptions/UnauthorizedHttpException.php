<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This exception is thrown when a user is not logged in and tries to access a page that requires authentication
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Exceptions;

use Pecee\SimpleRouter\Exceptions\HttpException;
use Throwable;

/**
 * Occurs when a user is not logged in and tries to access a page that requires authentication
 */
class UnauthorizedHttpException extends HttpException
{
    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link https://php.net/manual/en/exception.construct.php
     * @param string $message          [optional] The Exception message to throw.
     * @param int $code                [optional] The Exception code (should always be 401).
     * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct(string $message = 'Vous devez être connecter pour effectuer cette action.', int $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
