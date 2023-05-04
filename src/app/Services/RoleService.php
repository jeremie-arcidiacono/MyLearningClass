<?php

declare(strict_types=1);

namespace App\Services;

use App\App;
use App\Models\Role;
use Doctrine\ORM\Exception\ORMException;

class RoleService extends Service
{
    protected static string $model = Role::class;

    /**
     * @inheritDoc
     */
    public static function getModel(): string
    {
        return static::$model;
    }

    public static function FindByName(string $name): ?Role
    {
        try {
            return App::$db->getRepository(static::$model)->findOneBy(['name' => $name]);
        } catch (ORMException $e) {
            return null;
        }
    }

    public static function Find(int $id): ?Role
    {
        /** @var ?Role $role */
        $role = parent::FindGeneric($id);
        return $role;
    }

    public static function Create(Role $role, bool $autoFlush = true): ?Role
    {
        /** @var ?Role $role */
        $role = parent::CreateGeneric($role, $autoFlush);
        return $role;
    }

    public static function Update(Role $role, bool $autoFlush = true): ?Role
    {
        /** @var ?Role $role */
        $role = parent::UpdateGeneric($role, $autoFlush);
        return $role;
    }

    public static function Delete(Role $role, bool $autoFlush = true): bool
    {
        return parent::DeleteGeneric($role, $autoFlush);
    }
}
