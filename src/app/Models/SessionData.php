<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a Doctrine entity representing a data stored in a session.
 *                  This entity should only be used by the session driver (see config/session.php)
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Contracts\IModel;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, HasLifecycleCallbacks, Id, JoinColumn, ManyToOne, Table};

/**
 *  Entity representing a data stored in a session.
 */
#[Entity, Table(name: 'SESSION_DATA')]
#[HasLifecycleCallbacks]
class SessionData implements IModel
{
    #[Column(name: 'idSessionData', length: 20, options: ['unsigned' => true])]
    #[Id, GeneratedValue]
    private int $id;

    #[Column(name: 'dataKey', length: 64)]
    private string $key;

    #[Column(name: 'dataValue', options: ['nullable' => true])]
    private string $value;

    #[Column(name: 'isFlash', options: ['default' => 0])]
    private bool $isFlash;

    #[ManyToOne(targetEntity: Session::class, cascade: ['persist'], inversedBy: 'data')]
    #[JoinColumn(name: 'idSession', referencedColumnName: 'idSession')]
    private Session $session;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isFlash(): bool
    {
        return $this->isFlash;
    }

    /**
     * @param bool $isFlash
     */
    public function setIsFlash(bool $isFlash): void
    {
        $this->isFlash = $isFlash;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session): void
    {
        $this->session = $session;
    }


}
