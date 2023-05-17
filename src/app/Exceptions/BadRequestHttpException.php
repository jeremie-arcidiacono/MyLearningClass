<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This exception is thrown when a user do an action that he is not allowed to do.
 *                  The difference with the ForbiddenHttpException is that this exception is thrown not for permission
 *                  reasons but for business logic reasons.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Exceptions;

use Pecee\SimpleRouter\Exceptions\HttpException;
use Throwable;

/**
 * This exception is thrown when a user does an action that they are not allowed to do (for business logic reasons).
 */
class BadRequestHttpException extends HttpException
{
    /**
     * @param string $message          [optional] The Exception message to throw.
     * @param int $code                [optional] The Exception code (should always be 400).
     * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct(string $message = 'Bad request', int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
