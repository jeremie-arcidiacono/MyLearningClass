<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class if the service for the User model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\App;
use App\Models\User;
use Doctrine\ORM\Exception\ORMException;

/**
 * Database service class for the User model
 */
class UserService extends Service
{
    protected static string $model = User::class;

    /**
     * @inheritDoc
     */
    public static function getModel(): string
    {
        return static::$model;
    }

    /**
     * Find a user by its email
     * @param string $email
     * @return User|null
     */
    public static function FindByEmail(string $email): ?User
    {
        try {
            return App::$db->getRepository(static::$model)->findOneBy(['email' => $email]);
        } catch (ORMException $e) {
            return null;
        }
    }

    /**
     * @param int $id
     * @return User|null
     */
    public static function Find(int $id): ?User
    {
        /** @var ?User $user */
        $user = parent::FindGeneric($id);
        return $user;
    }

    /**
     * @param User $user
     * @param bool $autoFlush
     * @return User|null
     */
    public static function Create(User $user, bool $autoFlush = true): ?User
    {
        /** @var ?User $user */
        $user = parent::CreateGeneric($user, $autoFlush);
        return $user;
    }

    /**
     * @param User $user
     * @param bool $autoFlush
     * @return User|null
     */
    public static function Update(User $user, bool $autoFlush = true): ?User
    {
        /** @var ?User $user */
        $user = parent::UpdateGeneric($user, $autoFlush);
        return $user;
    }

    /**
     * @param User $user
     * @param bool $autoFlush
     * @return bool
     */
    public static function Delete(User $user, bool $autoFlush = true): bool
    {
        return parent::DeleteGeneric($user, $autoFlush);
    }
}
