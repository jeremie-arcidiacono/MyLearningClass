<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    February 2023
 * Description :    This class is used by the router (Pecee\SimpleRouter) to load the controllers, middlewares, etc.
 *                  The custom loader add a new feature : the ability for a controller method to get a model instance from the database.
 *                      If we have a route like this : /users/{userId}
 *                      When the user make a request with the url : /users/1
 *                      The loader will automatically get the user with the id 1 from the database and pass it to the controller method.
 *                      The controller method will be like this : public function show(User $user)
 *                      If the id is not found in the database, the loader will throw an EntityNotFoundHttpException.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Routing;

use App\App;
use App\Exceptions\EntityNotFoundHttpException;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Exception\ORMException;
use HaydenPierce\ClassFinder\ClassFinder;
use Pecee\SimpleRouter\ClassLoader\IClassLoader;
use Pecee\SimpleRouter\Exceptions\ClassNotFoundHttpException;

/**
 * Used by the router to load the controllers, middlewares, etc.
 * Add the ability to pass a model instance to a controller method.
 */
class ClassLoader implements IClassLoader
{
    /**
     * Load class
     *
     * @param string $class
     * @return object
     * @throws ClassNotFoundHttpException
     */
    public function loadClass(string $class): object
    {
        if (class_exists($class) === false) {
            throw new ClassNotFoundHttpException($class, null, sprintf('Class "%s" does not exist', $class), 404, null);
        }

        return new $class();
    }

    /**
     * Called when loading class method
     * @param object $class
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws EntityNotFoundHttpException
     */
    public function loadClassMethod(object $class, string $method, array $parameters): mixed
    {
        try {
            $parameters = $this->bindModelByRouteParameters($parameters);
        } catch (EntityNotFoundException $e) {
            throw new EntityNotFoundHttpException($e->getMessage(), previous: $e);
        }

        return call_user_func_array([$class, $method], array_values($parameters));
    }

    /**
     * Lookup in the route parameters for a model ID. (An entity name followed by 'Id')
     * If found, try to get the model from the database and bind it to the parameters.
     * If not found, return the original parameters.
     *
     * Example: If the route is /posts/{postId} and the postId is 1, the Post model with id 1 will be bound to the parameters postId.
     *
     * @param array $parameters
     * @return array
     * @throws EntityNotFoundException If the model with the given id does not exist in the database.
     */
    private function bindModelByRouteParameters(array $parameters): array
    {
        try {
            $modelsClasses = ClassFinder::getClassesInNamespace('App\Models', ClassFinder::STANDARD_MODE);
        } catch (\Exception $e) {
            return $parameters;
        }

        // Edit the string value as : 'App\Models\Event' => 'eventId'
        // This will be used to check if a route parameter is a model id
        $modelsParamsNames = array_combine(
            $modelsClasses,
            array_map(function ($modelClass) {
                return lcfirst(substr($modelClass, strrpos($modelClass, '\\') + 1)) . 'Id';
            }, $modelsClasses)
        );

        foreach ($parameters as $parameter => $modelId) {
            if (is_numeric($modelId) && in_array($parameter, $modelsParamsNames)) {
                $modelClass = array_search($parameter, $modelsParamsNames);
                try {
                    $model = App::$db->find($modelClass, $modelId);
                } catch (ORMException $e) {
                    throw new EntityNotFoundException("The item of type $modelClass with id $modelId does not exist.", 404, $e);
                }
                if ($model !== null) {
                    $parameters[$parameter] = $model;
                }
                else {
                    throw new EntityNotFoundException("The item of type $modelClass with id $modelId does not exist.", 404);
                }
            }
        }

        return $parameters;
    }

    /**
     * Load closure
     *
     * @param Callable $closure
     * @param array $parameters
     * @return mixed
     */
    public function loadClosure(callable $closure, array $parameters): mixed
    {
        return call_user_func_array($closure, array_values($parameters));
    }
}
