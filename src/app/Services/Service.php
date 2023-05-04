<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    February 2023
 * Description :    The class Service is the parent of all services.
 *                  A service is linked to a model class and is used to create, read, update and delete data from the database.
 *                  By using the service class, we can interact with models without having to know the database and EntityManager.
 *                  This provides a better abstraction of the database.
 *
 *                  So in theory, we should never use the EntityManager in the controllers and create a service for each model.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Services;

use App\App;
use App\Contracts\IModel;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;


/**
 * Each service class is linked to a model class.
 * The service class is used to create, read, update and delete data from the database.
 * By using the service class, we can interact with models without having to know the database and EntityManager.
 *
 * The method 'Generic' take an IModel as parameter and return an IModel.
 * The class that extends Service should create a method that call the 'Generic' method and cast the IModel to the correct model.
 * This is because PHP doesn't support parameter type covariance.
 * See : https://www.php.net/manual/en/language.oop5.variance.php
 * See : https://en.wikipedia.org/wiki/Liskov_substitution_principle
 */
abstract class Service
{
    /**
     * @return string The model of the service (ex: App\Models\User for UserService)
     */
    abstract public static function getModel(): string;

    /**
     * Find all the records of the model.
     * @return array An array of models (or empty array if no records are found)
     */
    public static function FindAll(): array
    {
        return static::GetRepository()->findAll();
    }

    /**
     * Find an entity by its id.
     * @param int|string $id
     * @return IModel|null Null if the entity is not found
     */
    protected static function FindGeneric(int|string $id): ?IModel
    {
        try {
            return static::GetRepository()->findOneBy(['id' => $id]);
        } catch (ORMException $e) {
            return null;
        }
    }

    /**
     * Persist the model in the database.
     * @param IModel $model
     * @param bool $autoFlush If true, the EntityManager will be flushed after the model is created.
     * @return IModel|null Null if the model could not be created
     */
    protected static function CreateGeneric(IModel $model, bool $autoFlush = true): ?IModel
    {
        if (!self::ModelIsInstanceOfThisModel($model)) {
            throw new \InvalidArgumentException('Model is not an instance of ' . static::getModel());
        }

        try {
            App::$db->persist($model);

            if ($autoFlush) {
                static::Flush();
            }

            return $model;
        } catch (ORMException $e) {
            return null;
        }
    }

    /**
     * Update the model in the database.
     * @param IModel $model
     * @param bool $autoFlush If true, the EntityManager will be flushed after the model is updated.
     * @return IModel|null Null if the model could not be updated
     */
    protected static function UpdateGeneric(IModel $model, bool $autoFlush = true): ?IModel
    {
        if (!self::ModelIsInstanceOfThisModel($model)) {
            throw new \InvalidArgumentException('Model is not an instance of ' . static::getModel());
        }

        try {
            App::$db->persist($model);

            if ($autoFlush) {
                static::Flush();
            }

            return $model;
        } catch (ORMException $e) {
            return null;
        }
    }

    /**
     * Remove a model from the database.
     * @param IModel $model
     * @param bool $autoFlush If true, the EntityManager will be flushed after the model is deleted.
     * @return bool Return false if the model could not be found or if the model could not be deleted.
     */
    protected static function DeleteGeneric(IModel $model, bool $autoFlush = true): bool
    {
        if (!self::ModelIsInstanceOfThisModel($model)) {
            throw new \InvalidArgumentException('Model is not an instance of ' . static::getModel());
        }

        try {
            App::$db->remove($model);
            if ($autoFlush) {
                static::Flush();
            }
            return true;
        } catch (OptimisticLockException|TransactionRequiredException|ORMException $e) {
            return false;
        }
    }


    /**
     * Return true if the model exists in the database.
     * @param IModel $model
     * @return bool
     */
    public static function EntityExists(IModel $model): bool
    {
        if (!self::ModelIsInstanceOfThisModel($model)) {
            throw new \InvalidArgumentException('Model is not an instance of ' . static::getModel());
        }
        return App::$db->contains($model);
    }

    /**
     * Check if the model is an instance of the model that this service is for.
     * E.g. an instance of User for the UserService, will return true.
     * @param IModel $IModel The model to check
     * @return bool True if this service is for the model, false otherwise
     */
    protected static function ModelIsInstanceOfThisModel(IModel $IModel): bool
    {
        return $IModel instanceof static::$model; // static::$model should be static::getModel() but PHP doesn't allow that
    }

    /**
     * Get the repository for the model of the service.
     * @return EntityRepository
     * @throws NotSupported
     */
    protected static function GetRepository(): \Doctrine\ORM\EntityRepository
    {
        return App::$db->getRepository(static::getModel());
    }

    /**
     * Commit all the changes that the unit of work has tracked.
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public static function Flush(): void
    {
        App::$db->flush();
    }

}
