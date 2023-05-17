<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    March 2023
 * Description :    The class extends the BladeOne template engine.
 *                  It adds a custom function to include a CSRF token in a form.
 *                  It also adds multiple shared variables to the views (see the constructor).
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\TemplateEngine;

use App\App;
use App\Auth;
use App\Config;
use App\Contracts\ISession;
use eftec\bladeone\BladeOne;
use Pecee\Http\Request;


/**
 * The class extends the BladeOne template engine and add some features.
 */
class TemplateEngine extends BladeOne
{
    use CsrfToken;

    /**
     * @param Config $config
     * @param Request $request
     * @param ISession $session
     * @param Auth $auth
     */
    public function __construct(
        private readonly Config $config,
        private readonly Request $request,
        private readonly ISession $session,
        private readonly Auth $auth
    ) {
        parent::__construct(
            templatePath: VIEWS_PATH,
            compiledPath: STORAGE_PATH . '/app/viewsCache',
            // Put slow mode in production to avoid using a compiled view who has a non-updated csrf token
            // (that would be a security issue, every user would have the same csrf token, and it would never change until the cache is cleared !)
            mode: App::isDevMode() ? BladeOne::MODE_DEBUG : BladeOne::MODE_SLOW,
        );

        // We add some variables to the all the views (just to be more convenient):
        //  - $appName: the name of the application
        //  - $currentRouteName: the name of the current route
        //  - $auth: the Auth instance
        //  - $config: the Config instance
        //  - $errors: the errors to display (from the session)
        //  - $old: the old values of the form (from the session)
        $this->composer('*', static function (TemplateEngine $view) {
            $view->share('appName', $view->config->get('app.name'));

            if (count($view->request->getLoadedRoutes()) > 0) {
                $currentRouteName = $view->request->getLoadedRoutes()[0]->getName();
            }
            else {
                $currentRouteName = null;
            }
            $view->share('currentRouteName', $currentRouteName);

            $view->share('auth', $view->auth);
            $view->share('config', $view->config);

            // Check if share errors is already set
            if (!isset($view->variablesGlobal['errors'])) {
                if ($view->session->hasFlash(ISession::ERROR_KEY)) {
                    $view->share('errors', $view->session->getFlash(ISession::ERROR_KEY));
                }
                else {
                    $view->share('errors', []);
                }
            }

            // Check if share stickyForm is already set
            if (!isset($view->variablesGlobal['old'])) {
                if ($view->session->hasFlash(ISession::STICKY_FORM_KEY)) {
                    $view->share('old', $view->session->getFlash(ISession::STICKY_FORM_KEY));
                }
                else {
                    $view->share('old', []);
                }
            }
        });
    }
}
