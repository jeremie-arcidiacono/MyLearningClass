<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    March 2023
 * Description :    This class is used as a session storage for the app. It replaces the default $_SESSION.
 *                  It uses the database to store the session data, but the flash data in the default PHP session (as file).
 *                  Also called : session driver 'hybrid'
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

/**
 * Use this class to store data in the database, but the flash data in the default PHP session.
 */
class HybridSession implements Contracts\ISession
{
    private NativeSession $nativeSession;
    private MysqlSession $mysqlSession;

    /**
     * @param EntityManager $db The database connection.
     * @param Config $config
     */
    public function __construct(EntityManager $db, private readonly Config $config)
    {
        $this->mysqlSession = new MysqlSession($db, $config);
        $this->nativeSession = new NativeSession($config);
    }

    /**
     * Start the session. The session is now ready to use.
     * @param string|null $sessId The session ID to use. If null, a new session ID will be generated.
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Exception
     */
    public function start(?string $sessId = null): void
    {
        $this->mysqlSession->start($sessId);
        $this->nativeSession->start($this->mysqlSession->getId());
    }

    /**
     * @inheritDoc
     */
    public function close(): bool
    {
        return $this->mysqlSession->close() && $this->nativeSession->close();
    }

    /**
     * @inheritDoc
     */
    public function getId(): ?string
    {
        return $this->mysqlSession->getId();
    }

    /**
     * Same as close(), but also destroy the session data. The session does not exist anymore
     * @return void
     * @throws ORMException
     */
    public function destroy(): void
    {
        $this->nativeSession->destroy();
        $this->mysqlSession->destroy();
    }

    /**
     * Change the session ID but keep the session data
     * This is used to prevent session fixation
     * @return bool
     * @throws NotSupported
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Exception
     */
    public function regenerate(): bool
    {
        $success = $this->mysqlSession->regenerate();
        if ($success) {
            $flashData = $this->nativeSession->exportFlash();
            $this->nativeSession->destroy();

            $newNativeSession = new NativeSession($this->config);
            $newNativeSession->start($this->mysqlSession->getId());
            $newNativeSession->importFlash($flashData);
            $this->nativeSession = $newNativeSession;
        }

        // Make sure nothing went wrong.
        if ($this->mysqlSession->getId() !== $this->nativeSession->getId()) {
            throw new \RuntimeException('Session IDs do not match.');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return $this->nativeSession->isActive() && $this->mysqlSession->isActive();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->mysqlSession->get($key, $default);
    }

    /**
     * Add/modify a value to the session
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws ORMException
     */
    public function set(string $key, mixed $value): void
    {
        $this->mysqlSession->set($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->mysqlSession->has($key);
    }

    /**
     * Remove a value from the session
     * @param string $key
     * @return void
     * @throws ORMException
     */
    public function remove(string $key): void
    {
        $this->mysqlSession->remove($key);
    }

    /**
     * @inheritDoc
     */
    public function setFlash(string $key, array|string $value): void
    {
        $this->nativeSession->setFlash($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function getFlash(string $key, string|array|null $default = null): string|array|null
    {
        return $this->nativeSession->getFlash($key, $default);
    }

    /**
     * @inheritDoc
     */
    public function hasFlash(string $key): bool
    {
        return $this->nativeSession->hasFlash($key);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return array_merge($this->nativeSession->getAll(), $this->mysqlSession->getAll());
    }
}
