<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    January 2023
 * Description :    This test class is used to test the NativeSession class.
 ** * * * * * * * * * * * * * * * * * * * * * * */

namespace Tests\Unit;

use App\Config;
use App\NativeSession;
use PHPUnit\Framework\MockObject\Exception;

/**
 * Test class for the NativeSession class.
 *
 * @uses \App\NativeSession
 * @uses \App\Config
 */
class NativeSessionTest extends SessionTestBase
{
    /**
     * Set te session to test and call the parent method to start the session.
     * @throws Exception Problem with the mock object 'Config'
     */
    public function setUp(): void
    {
        $this->session = new NativeSession($this->createMock(Config::class));

        parent::setUp();
    }
}
