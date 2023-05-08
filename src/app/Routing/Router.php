<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This class is a wrapper for Pecee\SimpleRouter.
 *                  It allows to add some custom methods to the router and autoload the routes and helpers.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Routing;

use App\Contracts\ISession;
use App\Routing\Csrf\CustomCsrfVerifier;
use App\Routing\Csrf\SessionTokenProvider;
use Pecee\Http\Security\Exceptions\SecurityException;
use Pecee\SimpleRouter\SimpleRouter;

/**
 * Wrapper for Pecee\SimpleRouter.
 * Used by the application to communicate with the router.
 */
class Router extends SimpleRouter
{
    /**
     * Enable CSRF verifier with a storage of the token in the session
     * @param ISession $session
     * @param int $tokenLength
     * @return void
     * @throws SecurityException If the token cannot be generated
     */
    public static function enableCsrfVerifier(ISession $session, int $tokenLength = 32): void
    {
        $csrfVerifier = new CustomCsrfVerifier();
        $csrfVerifier->setTokenProvider(new SessionTokenProvider($session, $tokenLength));
        parent::csrfVerifier($csrfVerifier);
    }

    /**
     * Load the routes and helpers, set the custom class loader and other settings.
     */
    public static function init(): void
    {
        // Load some helpers methods related to routing
        require_once ROOT_PATH . '/app/Routing/helpers.php';

        // Load the routes
        require_once ROOT_PATH . '/app/Routing/routes.php';

        // New class loader with auto model binding
        parent::setCustomClassLoader(new ClassLoader());

        parent::enableMultiRouteRendering(false);
    }
}
