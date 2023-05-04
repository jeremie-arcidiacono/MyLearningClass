<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This class is used as a session storage for the app.
 *                  It provides a wrapper for the default $_SESSION.
 *                  Also called : session driver 'file'
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App;

use App\Contracts\ISession;

/**
 * Use this class to store data in the default PHP session.
 */
class NativeSession implements Contracts\ISession
{
    /**
     * @param Config $config
     */
    public function __construct(private readonly Config $config)
    {
    }

    /**
     * Start the session. The session is now ready to use.
     * @param string|null $sessId The session ID to use. If null, a new session ID will be generated.
     * @return void
     * @throws \Exception
     */
    public function start(?string $sessId = null): void
    {
        if ($this->isActive()) {
            return;
        }

        if (headers_sent()) {
            throw new \Exception('Session cannot be started : HTTP headers already sent');
        }

        session_name($this->config->get('session.cookie.name'));
        session_set_cookie_params(
            [
                'httponly' => $this->config->get('session.cookie.httponly'),
                'samesite' => $this->config->get('session.cookie.samesite'),
                'secure' => $this->config->get('session.cookie.secure'),
                'path' => $this->config->get('session.cookie.path'),
                'domain' => $this->config->get('session.cookie.domain'),
            ]
        );

        // Set the session save path
        session_save_path($this->config->get('session.path'));

        // Set the session ID if provided
        if ($sessId !== null) {
            ini_set('session.use_strict_mode', '0'); // This is not recommended, but we need to do it to allow setting the session ID
            session_id($sessId);
        }
        else {
            ini_set('session.use_strict_mode', '1');
        }

        session_start();
    }

    /**
     * @inheritDoc
     */
    public function close(): bool
    {
        return session_write_close();
    }


    /**
     * @inheritDoc
     */
    public function getId(): ?string
    {
        if (!$this->isActive()) {
            return null;
        }
        return session_id();
    }

    /**
     * @inheritDoc
     */
    public function destroy(): void
    {
        $_SESSION = [];
        session_destroy();

        // Delete the session cookie
        setcookie(
            session_name(),
            '',
            [
                'expires' => time() - 3600,
                'path' => $this->config->get('session.cookie.path'),
                'domain' => $this->config->get('session.cookie.domain'),
                'secure' => $this->config->get('session.cookie.secure'),
                'httponly' => $this->config->get('session.cookie.httponly'),
                'samesite' => $this->config->get('session.cookie.samesite'),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function regenerate(): bool
    {
        return session_regenerate_id(true);
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (!$this->has($key)) {
            return $default;
        }
        return $_SESSION[$key];
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value): void
    {
        if ($value === null) { // We do not want to store null values in the session
            $this->remove($key);
            return;
        }
        $_SESSION[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        // We use isset() because we don't want to check if the value is null
        // To return true even if the value is null, use array_key_exists()
        return isset($_SESSION[$key]);
    }

    /**
     * @inheritDoc
     */
    public function remove(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    // This simple flash is used to store messages that will be displayed only once
    // The data will be deleted after the first call to getFlash()
    // The flash data should only be used for messages related to one request (not for data that will be used in the next request)
    /**
     * @inheritDoc
     */
    public function setFlash(string $key, string|array $value): void
    {
        $this->set(ISession::FLASH_KEY, array_merge($this->get(ISession::FLASH_KEY, []), [$key => $value]));
    }

    /**
     * @inheritDoc
     */
    public function getFlash(string $key, string|array|null $default = null): string|array|null
    {
        $messages = $this->get(ISession::FLASH_KEY, []);
        $messages = $messages[$key] ?? $default;
        unset($_SESSION[ISession::FLASH_KEY][$key]);
        return $messages;
    }

    /**
     * @inheritDoc
     */
    public function hasFlash(string $key): bool
    {
        return $this->has(ISession::FLASH_KEY) && array_key_exists($key, $this->get(ISession::FLASH_KEY));
    }

    /**
     * This method is used to export the flash messages to another session implementation.
     * The flash messages will be deleted from the current session.
     * @return array
     */
    public function exportFlash(): array
    {
        $messages = $this->get(Contracts\ISession::FLASH_KEY, []);
        unset($_SESSION[ISession::FLASH_KEY]);
        return $messages;
    }

    /**
     * This method is used to import flash messages from another session implementation.
     * @param array $messages
     * @return void
     */
    public function importFlash(array $messages): void
    {
        $this->set(ISession::FLASH_KEY, $messages);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $_SESSION;
    }
}
