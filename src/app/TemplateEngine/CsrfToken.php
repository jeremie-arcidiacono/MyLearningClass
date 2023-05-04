<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    March 2023
 * Description :    The trait extends the BladeOne template engine with a custom function to generate a CSRF token.
 *                  This is because we use the CSRF protection of SimpleRouter (Pecee\SimpleRouter), not the one of BladeOne.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\TemplateEngine;

use App\Routing\Router;

/**
 * Add a custom function to include a CSRF token in a form.
 */
trait CsrfToken
{
    /**
     * If the CSRF protection is enabled, get the current token and return it as a hidden input.
     * @return string
     */
    public function compileCustomCsrf(): string
    {
        if ($this->config->get('security.csrf.enabled') === false) {
            return '';
        }

        $fieldName = $this->config->get('security.csrf.token_name', 'csrf_token');
        $csrfToken = Router::router()->getCsrfVerifier()->getTokenProvider()->getToken();

        return "<input type=\"hidden\" name=\"$fieldName\" value=\"$csrfToken\">";
    }
}
