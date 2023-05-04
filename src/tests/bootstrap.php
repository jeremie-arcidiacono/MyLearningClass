<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    February 2023
 * Description :    This file load all the requirements needed to run the tests with PHPUnit.
 ** * * * * * * * * * * * * * * * * * * * * * * */

require_once __DIR__ . '/../vendor/autoload.php';

// Load constants
require_once __DIR__ . '/../config/constants.php';

// Load global helpers methods
require_once ROOT_PATH . '/app/helpers.php';
