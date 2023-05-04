<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This interface must be implemented by all the session drivers.
 *                  It is used to give an abstraction layer to the session and not use the default $_SESSION.
 *                  It can store data as flash data (useful for displaying errors, sticky form data, etc.).
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Contracts;

/**
 * Interface for all the types of sessions.
 */
interface ISession
{
    /**
     * @var string The key used to store flash data in the session.
     *             This is used to store data that is only available for the next request.
     */
    public const FLASH_KEY = 'FLASH_DATA';

    /**
     * @var string The key used to store errors in the session.
     *             Usually stored as a flash message.
     */
    public const ERROR_KEY = 'ERRORS_DATA';

    /**
     * @var string The key used to store the form data in the session (to make it sticky).
     *             Usually stored as a flash message.
     */
    public const STICKY_FORM_KEY = 'STICKY_FORM_DATA'; // Used for sticky form (usually as flash)

    /**
     * Start the session. The session is now ready to use.
     * @param string|null $sessId The session ID to use. If null, a new session ID will be generated
     * @return void
     */
    public function start(?string $sessId = null): void;

    /**
     * Close the connection to the session. The session is not active anymore
     * @return bool
     */
    public function close(): bool;


    /**
     * Get the session ID. If the session is not started, return null
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * Same as close(), but also destroy the session data. The session does not exist anymore
     * @return void
     */
    public function destroy(): void;

    /**
     * Change the session ID but keep the session data
     * This is used to prevent session fixation
     * @return bool
     */
    public function regenerate(): bool;

    /**
     * Check if the session is available and ready to use
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Get a value from the session
     * @param string $key
     * @param mixed|null $default The default value to return if the key does not exist
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Add/modify a value in the session
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void;

    /**
     * Check if a key exists in the session
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Remove a value from the session
     * @param string $key
     * @return void
     */
    public function remove(string $key): void;

    /**
     * Add/modify a value in the session that will be available only once. It will be deleted after the first read
     * @param string $key
     * @param string|array $value
     * @return void
     */
    public function setFlash(string $key, string|array $value): void;

    /**
     * Get a value from the session that was set as a flash data. It will delete the data.
     * @param string $key
     * @param string|array|null $default The default value to return if the key does not exist
     * @return string|array|null
     */
    public function getFlash(string $key, string|array|null $default = null): string|array|null;

    /**
     * Check if a key exists in the session as a flash data. Does not delete the data.
     * @param string $key
     * @return bool
     */
    public function hasFlash(string $key): bool;

    /**
     * Get all the session data. Mainly used for debugging.
     * @return array
     */
    public function getAll(): array;
}
