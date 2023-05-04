<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    January 2023
 * Description :    This config file defines constants used in the app.
 *                  This file is not intended to be loaded by the \App\Config class.
 *                  Add it to the EXCLUDED_FILES array in the \App\Config class.
 ** * * * * * * * * * * * * * * * * * * * * * * */


// Define directories paths constants

/**
 * The root path of the app
 */
const ROOT_PATH = __DIR__ . '/..';

/**
 * The path to the config directory
 */
const CONFIG_PATH = __DIR__;

/**
 * The path to the storage directory, where sessions,
 * private assets (like when user upload private image), cache, etc. are stored
 */
const STORAGE_PATH = __DIR__ . '/../storage';

/**
 * The path to the Models directory, where the Doctrine entities are stored
 */
const MODELS_PATH = __DIR__ . '/../app/Models';

/**
 * The path to the Blade views directory
 */
const VIEWS_PATH = __DIR__ . '/../resources/views';
