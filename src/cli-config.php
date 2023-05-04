<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    March 2023
 * Description :    The file contains the configuration and load dependencies for the Doctrine Migrations CLI tool.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once __DIR__ . '/vendor/autoload.php';

use App\Config;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/config/constants.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

$config = new Config(CONFIG_PATH);

$migrationConfig = new PhpFile(CONFIG_PATH . '/migrations.php');

$ORMSetup = ORMSetup::createAttributeMetadataConfiguration([MODELS_PATH], isDevMode: true);

// This is for creating migrations for MySQL/MariaDB dialect
//$em = new EntityManager(
//    DriverManager::getConnection(
//        params: $config->get('database'),
//        config: $ORMSetup
//    ),
//    $ORMSetup
//);

// This is for creating migrations for SQLite dialect
$em = new EntityManager(
    DriverManager::getConnection(
        params: [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ],
        config: $ORMSetup
    ),
    $ORMSetup
);

return DependencyFactory::fromEntityManager($migrationConfig, new ExistingEntityManager($em));
