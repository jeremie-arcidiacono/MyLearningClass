<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a Doctrine entity representing a session.
 *                  This entity should only be used by the session driver (see config/session.php)
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Contracts\IModel;
use App\Models\Traits\HasTimestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Column, Entity, HasLifecycleCallbacks, Id, OneToMany, Table};

/**
 *  Entity representing a session.
 */
#[Entity, Table(name: 'SESSION')]
#[HasLifecycleCallbacks]
class Session implements IModel
{
    use HasTimestamps;

    #[Column(name: 'idSession')]
    #[Id]
    private string $id;

    #[OneToMany(mappedBy: 'session', targetEntity: SessionData::class, cascade: ['persist', 'remove', 'detach', 'merge'], orphanRemoval: true)]
    private Collection $data;

    public function __construct()
    {
        $this->id = bin2hex(random_bytes(32));
        $this->data = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Return all data from the session
     * @return Collection
     */
    public function getData(): Collection
    {
        return $this->data;
    }


    /**
     * Return data from the session by key
     * @param string $key
     * @return SessionData|null Returns null if key does not exist
     */
    public function getDataByKey(string $key): ?SessionData
    {
        foreach ($this->data as $data) {
            if ($data->getKey() === $key) {
                return $data;
            }
        }
        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $isFlash
     * @return Session
     */
    public function addData(string $key, mixed $value, bool $isFlash): Session
    {
        $data = new SessionData();
        $data->setKey($key);
        $data->setValue($value);
        $data->setIsFlash($isFlash);
        $data->setSession($this);
        $this->data->add($data);
        return $this;
    }

    /**
     * @param SessionData $data
     * @return Session
     */
    public function removeData(SessionData $data): Session
    {
        if ($this->data->contains($data)) {
            $this->data->removeElement($data);
        }
        return $this;
    }
}
