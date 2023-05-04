<?php

declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This class is the main class of the application.
 *                  It is designed to be instanced only once, in the index.php file (entry point of the application).
 *                  It is used to store all the global variables of the application.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App;

use App\Contracts\ISession;
use App\Routing\Router;
use App\TemplateEngine\TemplateEngine;
use Clockwork\DataSource\DBALDataSource;
use Clockwork\DataSource\PhpDataSource;
use Clockwork\DataSource\XdebugDataSource;
use Clockwork\Support\Vanilla\Clockwork;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;
use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\Http\Request;
use Pecee\Http\Response;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;

/**
 * Main class of the application.
 * Designed to be instanced only once in the index.php file (entry point of the application).
 * Used to store all the global variables of the application.
 */
class App
{
    public static EntityManager $db;
    public static Request $request;
    public static Response $response;
    public static Auth $auth;
    public static ISession $session;
    public static Config $config;

    /**
     * Only used when debug is enabled (set to null otherwise)
     * @var Clockwork|null
     */
    public static ?Clockwork $clockwork = null;

    public static TemplateEngine $templateEngine;

    /**
     * Instantiates all application components (database, session, model engine, etc.)
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws MissingMappingDriverImplementation
     * @throws Exception
     */
    public function __construct()
    {
        $this->loadDotEnv();

        // Load global helpers methods
        require_once __DIR__ . '/helpers.php';

        static::$config = new Config(CONFIG_PATH);

        // define the timezone
        date_default_timezone_set(static::$config->get('app.timezone', 'Europe/Zurich'));

        if (static::isDebugEnabled()) {
            static::$clockwork = Clockwork::init([
                'storage_files_path' => STORAGE_PATH . '/app/clockwork',
                'register_helpers' => true,
            ]);
            static::$clockwork->getClockwork()->addDataSource(new PhpDataSource());
            static::$clockwork->getClockwork()->addDataSource(new XdebugDataSource());
        }

        $ORMConfiguration = ORMSetup::createAttributeMetadataConfiguration([MODELS_PATH], isDevMode: true);
        $ORMConnexion = DriverManager::getConnection(
            params: static::$config->get('database'),
            config: $ORMConfiguration
        );
        static::$db = new EntityManager(
            $ORMConnexion,
            $ORMConfiguration
        );
        if (self::isDebugEnabled()) {
            static::$clockwork->getClockwork()->addDataSource(new DBALDataSource($ORMConnexion));
        }

        self::$request = Router::request();
        self::$response = Router::response();

        // Define the session driver
        if (static::$config->get('session.driver') === 'mysql') {
            self::$session = new MysqlSession(self::$db, self::$config);
        }
        elseif (static::$config->get('session.driver') === 'hybrid') {
            self::$session = new HybridSession(self::$db, self::$config);
        }
        else {
            self::$session = new NativeSession(self::$config);
        }
        self::$session->start();
        if (self::isDebugEnabled()) {
            static::$clockwork->getClockwork()->addDataSource(new CustomSessionDataSource(self::$session));
        }

        self::$auth = new Auth(self::$session);


        // Load the blade engine, with all the shared variables
        self::$templateEngine = new TemplateEngine(
            self::$config,
            self::$request,
            self::$session,
            self::$auth,
        );

        // Enable CSRF protection
        if (static::$config->get('security.csrf.enabled') === true) {
            Router::enableCsrfVerifier(self::$session, static::$config->get('security.csrf.token_length'));
        }
    }

    /**
     * Return true if the application is in a development environment
     * @return bool
     */
    public static function isDevMode(): bool
    {
        if (!isset(static::$config)) {
            $env = $_ENV['APP_ENV'] ?? 'production';
        }
        else {
            $env = static::$config->get('app.env');
        }
        return in_array(strtolower($env), ['dev', 'local', 'development']);
    }

    /**
     * Return true if the debug mode is enabled
     * @return bool
     */
    public static function isDebugEnabled(): bool
    {
        if (!isset(static::$config)) {
            // The config object is not yet instantiated, we need to read the .env file directly
            $debug = $_ENV['APP_DEBUG'] ?? false;
            $debug = in_array(strtolower($debug), ['true', '1']);
        }
        else {
            $debug = static::$config->get('app.debug');
        }
        return $debug;
    }

    /**
     * Starts the processing of the request by the router and sends the response to the client.
     * @throws TokenMismatchException
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function run(): void
    {
        if (self::isDebugEnabled()) {
            ob_start(); // Start output buffering to be able to process the response with Clockwork before sending it to the client

            Router::start();

            static::$clockwork->requestProcessed();

            echo ob_get_clean();
        }
        else {
            Router::start();
        }
    }

    /**
     * Load the .env file with the Dotenv library and check if all the required variables are set.
     * @return void
     */
    protected function loadDotEnv(): void
    {
        $dotenv = Dotenv::createImmutable(ROOT_PATH);
        $dotenv->load();

        $dotenv->required([ // Required and not empty
            'APP_NAME',
            'APP_ENV',
            'APP_DOMAIN',
            'DB_HOST',
            'DB_NAME',
            'DB_USER'
        ])->notEmpty();

        $dotenv->required([ // Required
            'DB_PASSWORD',
        ]);

        $dotenv->required([ // Required and not empty and boolean
            'APP_DEBUG',
            'RECAPTCHA_ENABLED',
        ])->notEmpty()->allowedValues(['true', 'false', '1', '0']);

        // Required and not empty and one of the allowed values
        $dotenv->required('DB_DRIVER')->notEmpty()->allowedValues(['pdo_mysql', 'mysqli']);

        // Required and not empty and one of the allowed values
        $dotenv->required('SESSION_DRIVER')->notEmpty()->allowedValues(['file', 'mysql', 'hybrid']);
    }
}
