<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a Doctrine entity representing a role (alias for a group of permissions)
 *                  E.g. 'admin' or 'user' role
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Contracts\IModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Column, Entity, Id, InverseJoinColumn, JoinColumn, JoinTable, ManyToMany, OneToMany, Table};

/**
 * Entity representing a role (alias for a group of permissions)
 */
#[Entity, Table(name: 'ROLE')]
class Role implements IModel
{
    #[Column(name: 'codeRole', length: 2, options: ['unsigned' => true])]
    #[Id]
    private int $code;

    #[Column(length: 20, unique: true)]
    private string $name;

    #[Column(length: 40)]
    private string $label;

    #[OneToMany(mappedBy: 'role', targetEntity: User::class)]
    private Collection $users;

    #[JoinTable(name: 'ROLE_HAS_PERMISSION')]
    #[JoinColumn(name: 'codeRole', referencedColumnName: 'codeRole')]
    #[InverseJoinColumn(name: 'codePermission', referencedColumnName: 'codePermission')]
    #[ManyToMany(targetEntity: Permission::class, cascade: ['persist'])]
    private Collection $permissions;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }


    /**
     * Get the ID of the model.
     * @return int
     */
    public function getId(): int
    {
        return $this->getCode();
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return Role
     */
    public function setCode(int $code): Role
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Role
     */
    public function setName(string $name): Role
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Role
     */
    public function setLabel(string $label): Role
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection Users with this role
     */
    public function getUsers(): ArrayCollection|Collection
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return Role
     */
    public function addUser(User $user): Role
    {
        $this->users->add($user);
        return $this;
    }

    /**
     * @param User $user
     * @return Role
     */
    public function removeUser(User $user): Role
    {
        $this->users->removeElement($user);
        return $this;
    }

    /**
     * @return ArrayCollection|Collection Permissions for this role
     */
    public function getPermissions(): ArrayCollection|Collection
    {
        return $this->permissions;
    }

    /**
     * @param Permission $permission
     * @return Role
     */
    public function addPermission(Permission $permission): Role
    {
        $this->permissions->add($permission);
        return $this;
    }

    /**
     * @param Permission $permission
     * @return Role
     */
    public function removePermission(Permission $permission): Role
    {
        $this->permissions->removeElement($permission);
        return $this;
    }
}
