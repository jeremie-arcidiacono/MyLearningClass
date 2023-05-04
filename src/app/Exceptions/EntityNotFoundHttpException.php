<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    March 2023
 * Description :    This exception is thrown when a user try to access a page of an entity that does not exist
 *                  For exemple if the url is "/user/999", but user with id 999 does not exist.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Exceptions;

use Pecee\SimpleRouter\Exceptions\HttpException;
use Throwable;

/**
 * Occurs when a user try to access a page of an entity that does not exist
 * E.g. URL: "/user/999", but user with id 999 does not exist
 */
class EntityNotFoundHttpException extends HttpException
{
    /**
     * @param string $message          [optional] The Exception message to throw.
     * @param int $code                [optional] The Exception code (should always be 404).
     * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct(string $message, int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
