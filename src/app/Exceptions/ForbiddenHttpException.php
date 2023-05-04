<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    February 2023
 * Description :    This exception is thrown when a user try to access a page or do an action that he is not allowed
 *                  to do (for permissions reasons or for other reasons)
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Exceptions;

use Pecee\SimpleRouter\Exceptions\HttpException;
use Throwable;

/**
 * Occurs when a user try to access a page or do an action that he is not allowed to do (for permissions reasons or for other reasons)
 */
class ForbiddenHttpException extends HttpException
{
    /**
     * @param string $message          [optional] The Exception message to throw.
     * @param int $code                [optional] The Exception code (should always be 403).
     * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct(string $message = 'Forbidden', int $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
