<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    April 2023
 * Description :    This class is used by the router (Pecee\SimpleRouter) to verify the CSRF token.
 *                  The only reason why this class exists is to add the possibility to exclude some routes from the CSRF verification.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Routing\Csrf;

use Pecee\Http\Middleware\BaseCsrfVerifier;

/**
 * This class is used by the router (Pecee\SimpleRouter) to verify the CSRF token.
 */
class CustomCsrfVerifier extends BaseCsrfVerifier
{
    // The CSRF token is not required for the following routes
    protected ?array $except = [];

    protected ?array $include = null;

    public function __construct()
    {
        $this->except[] = url('deconnexion');
    }
}
