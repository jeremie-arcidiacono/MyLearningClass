<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    April 2023
 * Description :    This class is used to manage the anti-CSRF token used by the router (Pecee\SimpleRouter\SimpleRouter).
 *                  It uses the session to store the token.
 *                  The class is inspired by the class Pecee\Http\Security\CookieTokenProvider
 ** * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Routing\Csrf;

use App\Contracts\ISession;
use Exception;
use Pecee\Http\Security\Exceptions\SecurityException;
use Pecee\Http\Security\ITokenProvider;

/**
 * Used by the router to manage the anti-CSRF token and store it in the session.
 */
class SessionTokenProvider implements ITokenProvider
{
    /**
     * @var string The key used to store the CSRF token in the session
     */
    public const CSRF_KEY = 'CSRF_TOKEN';

    protected ?string $token = null;

    /**
     * @param ISession $session
     * @param int $tokenLength
     * @throws SecurityException If the token cannot be generated
     */
    public function __construct(protected readonly ISession $session, protected int $tokenLength = 32)
    {
        $this->token = $this->session->get(static::CSRF_KEY);

        if ($this->token === null) {
            $this->token = $this->generateToken();
        }
    }

    /**
     * Generate random identifier for CSRF token
     *
     * @return string
     * @throws SecurityException If the token cannot be generated (error with built-in function random_bytes)
     */
    public function generateToken(): string
    {
        try {
            return bin2hex(random_bytes($this->tokenLength));
        } catch (Exception $e) {
            throw new SecurityException($e->getMessage(), (int)$e->getCode(), $e->getPrevious());
        }
    }

    /**
     * Refresh existing token
     */
    public function refresh(): void
    {
        if ($this->token !== null) {
            $this->setToken($this->token);
        }
    }

    /**
     * Validate valid CSRF token
     *
     * @param string $token
     * @return bool
     */
    public function validate(string $token): bool
    {
        if ($this->getToken() !== null) {
            return hash_equals($token, $this->getToken());
        }

        return false;
    }

    /**
     * Get csrf token
     * @param string|null $defaultValue
     * @return string|null
     */
    public function getToken(?string $defaultValue = null): ?string
    {
        return $this->token ?? $defaultValue;
    }

    /**
     * Set csrf token in the session
     * Overwrite this method to save the token to another storage like session etc.
     *
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
        $this->session->set(static::CSRF_KEY, $this->token);
    }

    /**
     * Returns whether the csrf token has been defined
     * @return bool
     */
    public function hasToken(): bool
    {
        return $this->session->has(static::CSRF_KEY);
    }

}
