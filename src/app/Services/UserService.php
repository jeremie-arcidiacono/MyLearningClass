<?php

declare(strict_types=1);

namespace App\Services;

use App\App;
use App\Models\User;
use Doctrine\ORM\Exception\ORMException;

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

    public static function FindByEmail(string $email): ?User
    {
        try {
            return App::$db->getRepository(static::$model)->findOneBy(['email' => $email]);
        } catch (ORMException $e) {
            return null;
        }
    }

    public static function Find(int $id): ?User
    {
        /** @var ?User $user */
        $user = parent::FindGeneric($id);
        return $user;
    }

    public static function Create(User $user, bool $autoFlush = true): ?User
    {
        /** @var ?User $user */
        $user = parent::CreateGeneric($user, $autoFlush);
        return $user;
    }

    public static function Update(User $user, bool $autoFlush = true): ?User
    {
        /** @var ?User $user */
        $user = parent::UpdateGeneric($user, $autoFlush);
        return $user;
    }

    public static function Delete(User $user, bool $autoFlush = true): bool
    {
        return parent::DeleteGeneric($user, $autoFlush);
    }
}
