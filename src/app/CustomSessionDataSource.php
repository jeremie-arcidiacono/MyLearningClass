<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This class is a data source for Clockwork that adds the session data to the request.
 *                  By default, Clockwork only adds the default PHP session to the request ($_SESSION).
 *                  But as we use a custom session (MySQL), we need to add it manually.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App;

use App\Contracts\ISession;
use Clockwork\DataSource\DataSource;
use Clockwork\Request\Request;

/**
 * Data source for Clockwork that adds the session data to the request. *
 */
class CustomSessionDataSource extends DataSource
{
    /**
     * @param ISession $session The session that is used by the application.
     */
    public function __construct(protected ISession $session)
    {
    }

    /**
     * @param Request $request
     * @return void
     */
    public function resolve(Request $request): void
    {
        $request->sessionData = $this->session->getAll();
    }
}
