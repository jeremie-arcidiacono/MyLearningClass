<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the Role model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\App;
use App\Models\Role;
use Doctrine\ORM\Exception\ORMException;

/**
 *  Database service class for the Role model
 */
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

    /**
     * Find a role by its name
     * @param string $name
     * @return Role|null
     */
    public static function FindByName(string $name): ?Role
    {
        try {
            return App::$db->getRepository(static::$model)->findOneBy(['name' => $name]);
        } catch (ORMException $e) {
            return null;
        }
    }

    /**
     * @param int $id
     * @return Role|null
     */
    public static function Find(int $id): ?Role
    {
        /** @var ?Role $role */
        $role = parent::FindGeneric($id);
        return $role;
    }
}
