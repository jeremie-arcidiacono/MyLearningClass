<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This class is used as a session storage for the app. It replaces the default $_SESSION.
 *                  It uses a relational database (with Doctrine ORM) to store all the session data.
 *                  Also called : session driver 'mysql'
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App;

use App\Models\Session;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

/**
 * Use this class to store all the session data in a database.
 */
class MysqlSession implements Contracts\ISession
{
    private ?Session $session = null; // The session object from the database (entity)

    /**
     * @param EntityManager $db The database connection.
     * @param Config $config
     */
    public function __construct(private readonly EntityManager $db, private readonly Config $config)
    {
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
        if ($this->isActive()) {
            return;
        }

        // Check if connection to database is working
        if (!$this->db->isOpen()) {
            throw new \Exception('Session cannot be started : Database connection is not working');
        }

        // Garbage collector process
        $this->garbageCollection();

        // Check if session cookie is set and exists in database
        $cookieValue = escape($_COOKIE[$this->config->get('session.cookie.name')] ?? null);
        if (!$cookieValue || !$session = $this->db->getRepository(Session::class)->find($cookieValue)) {
            // The session cookie is not set or does not exist in database : create a new session
            $sessId = $sessId ?? $this->getNewSessionId();

            // Insert new session in database
            $session = new Session();
            $session->setId($sessId);
            $this->db->persist($session);
        }
        else { // The session cookie is set and exists in database
            $session->setUpdatedAt(new \DateTime()); // Update the session last activity date
            $this->db->persist($session);
        }

        $this->db->flush();
        $this->session = $session;

        $this->sendCookie();
    }

    /**
     * @inheritDoc
     */
    public function close(): bool
    {
        $this->db->detach($this->session);
        $this->session = null;

        return $this->isActive();
    }

    /**
     * @inheritDoc
     */
    public function getId(): ?string
    {
        if (!$this->isActive()) {
            return null;
        }
        return $this->session->getId();
    }

    /**
     * Destroy the session and remove the session cookie
     * @return void
     * @throws ORMException
     */
    public function destroy(): void
    {
        $this->deleteCookie();

        $this->db->remove($this->session);
        $this->db->flush();
        $this->session = null;
        // We don't need to delete session data because it will be deleted by the database when the session is deleted (ON DELETE CASCADE)
    }

    /**
     * Change the session ID but keep the session data
     * This is used to prevent session fixation
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws NotSupported
     * @throws \Exception
     */
    public function regenerate(): bool
    {
        $oldSessionId = $this->session->getId();
        $this->session->setId($this->getNewSessionId());

        $this->db->detach($this->session);

        $this->db->persist($this->session);

        // Delete old session id
        $oldSession = $this->db->getRepository(Session::class)->find($oldSessionId);
        if ($oldSession) {
            $this->db->remove($oldSession);
        }

        $this->db->flush();

        $this->sendCookie();
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return isset($this->session);
    }


    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $sessionData = $this->session->getDataByKey($key);

        if (!$sessionData) {
            return $default;
        }
        return unserialize($sessionData->getValue());
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
        if ($value === null) { // We do not want to store null values in the session
            $this->remove($key);
            return;
        }

        $sessionData = $this->session->getDataByKey($key);

        if ($sessionData) { // If the session data already exists, we update it
            $sessionData->setValue(serialize($value));
        }
        else { // If the session data does not exist, we create it
            $this->session->addData($key, serialize($value), false);
        }

        $this->db->persist($this->session);
        $this->db->flush(); // We update the session because the session data has changed (cascade update)
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->session->getDataByKey($key) !== null;
    }

    /**
     * Remove a value from the session
     * @param string $key
     * @return void
     * @throws ORMException
     */
    public function remove(string $key): void
    {
        $sessionData = $this->session->getDataByKey($key);

        if ($sessionData) {
            $this->session->removeData($sessionData);
        }

        $this->db->persist($this->session);
        $this->db->flush(); // We update the session because the session data has changed (cascade update)
    }


    /**
     * Add/modify a flash value to the session.
     * The value will be deleted after the first call to getFlash()
     * @param string $key
     * @param string|array $value
     * @return void
     * @throws ORMException
     */
    public function setFlash(string $key, string|array $value): void
    {
        $sessionData = $this->session->getDataByKey($key);

        if ($sessionData) { // If the session data already exists, we update it
            $sessionData->setValue(serialize($value));
        }
        else { // If the session data does not exist, we create it
            $this->session->addData($key, serialize($value), true);
        }

        $this->db->persist($this->session);
        $this->db->flush(); // We update the session because the session data has changed (cascade update)
    }


    /**
     * Get a value from the session that was set as a flash data. It will delete the data.
     * @param string $key
     * @param string|array|null $default
     * @return string|array|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getFlash(string $key, string|array|null $default = null): string|array|null
    {
        $sessionData = $this->session->getDataByKey($key);

        if (!$sessionData) {
            return $default;
        }

        $value = unserialize($sessionData->getValue());

        if ($sessionData->isFlash()) { // We check if the data is really a flash data
            $this->remove($key);
            $this->db->persist($this->session);
            $this->db->flush(); // We update the session because the session data has changed (cascade update)
        }

        return $value;
    }

    /**
     * Return a new random session id
     * @return string
     * @throws \Exception If an error with the random_bytes() function occurs
     */
    private function getNewSessionId(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * @inheritDoc
     */
    public function hasFlash(string $key): bool
    {
        $sessionData = $this->session->getDataByKey($key);
        if (!$sessionData) {
            return false;
        }

        return $sessionData->isFlash();
    }


    /**
     * Send the session cookie to the client
     * @return void
     */
    private function sendCookie(): void
    {
        setcookie(
            $this->config->get('session.cookie.name'),
            $this->session->getId(),
            [
                'httponly' => $this->config->get('session.cookie.httponly'),
                'samesite' => $this->config->get('session.cookie.samesite'),
                'secure' => $this->config->get('session.cookie.secure'),
                'path' => $this->config->get('session.cookie.path'),
                'domain' => $this->config->get('session.cookie.domain'),
            ]
        );
    }

    /**
     * Remove the session cookie from the client
     * @return void
     */
    private function deleteCookie(): void
    {
        setcookie(
            $this->config->get('session.cookie.name'),
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
     * Garbage collection process is used to delete old sessions
     * @return void
     * @throws \Exception
     */
    public function garbageCollection(): void
    {
        $allSessions = $this->db->getRepository(Session::class)->findAll();

        foreach ($allSessions as $session) {
            if ($session->getCreatedAt() < new \DateTime('-' . $this->config->get('session.lifetime') . ' seconds')) {
                $this->db->remove($session);
                $this->db->flush();
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $sessionAllData = $this->session->getData();
        $data = [];
        foreach ($sessionAllData as $sessionData) {
            $data[$sessionData->getKey()] = unserialize($sessionData->getValue());
        }
        return $data;
    }
}
