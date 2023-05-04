<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This file contains some useful functions that can be used anywhere in the application.
 *                  The helpers functions are related to the routing and HTTP requests.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

use App\App;
use App\Routing\Router;
use Pecee\Http\Input\InputHandler;
use Pecee\Http\Input\InputItem;
use Pecee\Http\Url;

/**
 * Get url for a route by using either name/alias, class or method name.
 *
 * The name parameter supports the following values:
 * - Route name
 * - Controller/resource name (with or without method)
 * - Controller class name
 *
 * When searching for controller/resource by name, you can use this syntax "route.name@method".
 * You can also use the same syntax when searching for a specific controller-class "MyController@home".
 * If no arguments is specified, it will return the url for the current loaded route.
 *
 * @param string|null $name
 * @param array|string|null $parameters
 * @param array|null $getParams
 * @return Url
 * @throws InvalidArgumentException
 */
function getUrlObject(?string $name = null, array|string $parameters = null, ?array $getParams = null): Url
{
    return Router::getUrl($name, $parameters, $getParams);
}

/**
 * Get url for a route by using either name/alias, class or method name.
 *
 * The name parameter supports the following values:
 * - Route name
 * - Controller/resource name (with or without method)
 * - Controller class name
 *
 * When searching for controller/resource by name, you can use this syntax "route.name@method".
 * You can also use the same syntax when searching for a specific controller-class "MyController@home".
 * If no arguments is specified, it will return the url for the current loaded route.
 *
 * @param string|null $name
 * @param array|string|null $parameters
 * @param array|null $getParams
 * @return string The url without trailing slash
 * @throws InvalidArgumentException
 */
function url(?string $name = null, array|string $parameters = null, ?array $getParams = null): string
{
    $urlString = getUrlObject($name, $parameters, $getParams)->getRelativeUrl();
    if ($urlString === '/') { // If url is root, return it as is (to prevent issues when empty string is interpreted as the current url)
        return $urlString;
    }
    else {
        return rtrim($urlString, '/'); // Remove trailing slash
    }
}

/**
 * Get input class
 * @param string|null $index        Parameter index name
 * @param string|null $defaultValue Default return value
 * @param array ...$methods         Default methods
 * @return InputHandler|array|string|null
 */
function input(string $index = null, string $defaultValue = null, ...$methods): array|string|InputHandler|null
{
    if ($index !== null) {
        return Router::request()->getInputHandler()->value($index, $defaultValue, ...$methods);
    }

    return Router::request()->getInputHandler();
}

/**
 * Get all the GET/POST/PUT/DELETE inputs (not files).
 * The inputs are escaped by default
 * If a value is an empty string, it will be returned as null.
 * @return array E.g. ['name' => 'value', 'name2' => 'value2']
 */
function getAllInputs(): array
{
    $inputsItems = Router::request()->getInputHandler()->all();

    $inputs = [];

    foreach ($inputsItems as $inputItem) {
        // Check if input is really an InputItem (and not something else like file)
        if (!is_a($inputItem, InputItem::class)) {
            continue;
        }

        $inputs[$inputItem->getIndex()] = escape($inputItem->getValue());
    }

    return $inputs;
}

/**
 * Redirect to a specific url.
 * Use this method instead of header('Location: ...'); or SimpleRouter::response()->redirect(...); to ensure that
 * clockwork is able to track the request correctly.
 * @param string $url
 * @param int|null $code
 */
function redirect(string $url, ?int $code = null): never
{
    if ($code !== null) {
        http_response_code($code);
    }
    header('Location: ' . $url);

    if (App::isDebugEnabled()) {
        App::$clockwork->requestProcessed();
    }

    exit(0);
}
