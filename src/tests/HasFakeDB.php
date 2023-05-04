<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    February 2023
 * Description :    This trait provides a method to initialize a fake database in memory for testing purposes.
 ** * * * * * * * * * * * * * * * * * * * * * * */

namespace Tests;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use PHPUnit\Framework\Warning;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Include this trait in a test class to be able to use the method setUpFakeDb().
 * This method will initialize a new database in memory for simulating the real database of the app.
 */
trait HasFakeDB
{
    protected EntityManager $db;

    /**
     * This method must be called before every test who are related to the database.
     * It will initialize a new database in memory for simulating the real database of the app.
     * All the data will be lost when the method is called again. (Unless the $destroyPreviousDB parameter is set to false)
     * @param bool $destroyPreviousDB
     * @return void
     * @throws Exception
     * @throws MissingMappingDriverImplementation
     */
    protected function setUpFakeDb(bool $destroyPreviousDB = true): void
    {
        // If the database is already initialized
        if (isset($this->db)) {
            // If the database must be destroyed
            if ($destroyPreviousDB) {
                // Destroy the database
                unset($this->db);
                // Reinitialize the database
                $this->setUpFakeDb();
            }
            // If the database must not be destroyed
            else {
                // Do nothing and keep existing database
                return;
            }
        }

        //echo "Setting up fake database...";

        // Ensure that the alternative database can be used
        if (!extension_loaded('pdo_sqlite')) {
            //$this->markTestSkipped('The pdo_sqlite extension is not available.');
            throw new Warning('The pdo_sqlite extension is not available.');
        }

        // Create a fake EntityManager
        $ORMSetup = ORMSetup::createAttributeMetadataConfiguration([MODELS_PATH], isDevMode: true);
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

        // Migrate the database to the latest version
        $config = new PhpFile(CONFIG_PATH . '/migrations.php');
        $loader = new ExistingEntityManager($em);
        $dependencyFactory = DependencyFactory::fromEntityManager($config, $loader); // or use ::fromConnection()


        $version = $dependencyFactory->getVersionAliasResolver()->resolveVersionAlias('latest');
        $planCalculator = $dependencyFactory->getMigrationPlanCalculator();
        $plan = $planCalculator->getPlanUntilVersion($version);
        $migrator = $dependencyFactory->getMigrator();
        $migratorConfigurationFactory = $dependencyFactory->getConsoleInputMigratorConfigurationFactory();
        $migratorConfiguration = $migratorConfigurationFactory->getMigratorConfiguration(new ArrayInput([]));

        // Run the sync-metadata-storage command to ensure the metadata table is created
        $dependencyFactory->getMetadataStorage()->ensureInitialized();

        $migrator->migrate($plan, $migratorConfiguration);

        // Save the EntityManage
        $this->db = $em;
    }
}
