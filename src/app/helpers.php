<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    February 2023
 * Description :    This file contains common helper functions that can be used anywhere in the app.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

use App\App;
use App\Contracts\ISession;

/**
 * Transforms a string to a human friendly string (remove underscores and capitalize first letter)
 * Example : 'my_superString' => 'My super string'
 * @param string $value The string to transform
 * @return string
 */
function humanFriendly(string $value): string
{
    return ucfirst(str_replace('_', ' ', strtolower($value)));
}

/**
 * Escape a string or an array of strings (using htmlentities)
 * @param string|array|null $value The string or array of strings to escape
 * @return string|array|null
 */
function escape(string|array|null $value): string|array|null
{
    if (is_array($value)) {
        // Use recursion to escape all values of the array
        return array_map('escape', $value);
    }
    elseif (is_null($value)) {
        return null;
    }
    else {
        return htmlentities($value, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Unescape a string or an array of strings (using html_entity_decode)
 * @param string|array|null $value
 * @return string|array|null
 */
function unescape(string|array|null $value): string|array|null
{
    if (is_array($value)) {
        // Use recursion to escape all values of the array
        return array_map('unescape', $value);
    }
    elseif (is_null($value)) {
        return null;
    }
    else {
        return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Put the current user inputs ($_GET, $_POST, ...) in the session as a flash message.
 * @return void
 */
function flashInputs(): void
{
    flashInputsExcept([]);
}

/**
 * Put the current user inputs ($_GET, $_POST, ...) in the session as a flash message.
 * This is used to make the form sticky.
 * @param array $except An array of inputs to exclude from the flash message
 * @return void
 */
function flashInputsExcept(array $except): void
{
    $inputs = getAllInputs();

    foreach ($except as $exceptInput) {
        unset($inputs[$exceptInput]);
    }

    App::$session->setFlash(ISession::STICKY_FORM_KEY, $inputs);
}

/**
 * Put some user inputs ($_GET, $_POST, ...) in the session as a flash message.
 * @param array $only An array of inputs to include in the flash message
 * @return void
 */
function flashInputsOnly(array $only): void
{
    $inputs = getAllInputs();

    foreach ($inputs as $input => $value) {
        if (!in_array($input, $only)) {
            unset($inputs[$input]);
        }
    }

    App::$session->setFlash(ISession::STICKY_FORM_KEY, $inputs);
}

/**
 * Convert an array of strings to an array of enums.
 * All strings that cannot be converted to an enum are ignored.
 * It works with 1 type of enum at a time.
 * @param string[] $array  An array of strings
 * @param string $enumName The name of the enum to convert to
 * @return array An array of enums
 * @throws InvalidArgumentException If the enum does not exist
 */
function stringsToEnums(array $array, string $enumName): array
{
    if (!enum_exists($enumName)) {
        throw new InvalidArgumentException("Enum $enumName does not exist");
    }

    $enumArray = [];

    foreach ($array as $value) {
        $enum = $enumName::tryFrom($value);
        if ($enum !== null) {
            $enumArray[] = $enum;
        }
    }

    return $enumArray;
}
