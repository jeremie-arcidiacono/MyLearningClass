<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    March 2023
 * Description :    This test class is used to test the HybridSession class.
 ** * * * * * * * * * * * * * * * * * * * * * * */

namespace Tests\Unit;

use App\Config;
use PHPUnit\Framework\MockObject\Exception;
use Tests\HasFakeDB;

/**
 * Test class for the MysqlSession class.
 *
 * @uses \App\HybridSession
 * @uses \App\Config
 * @uses \Tests\HasFakeDB
 */
class HybridSessionTest extends SessionTestBase
{
    use HasFakeDB;

    /**
     * Set te session to test and call the parent method to start the session.
     * @throws Exception Problem with the mock object 'Config' or the fake database
     */
    public function setUp(): void
    {
        $this->setUpFakeDb();

        $this->session = new \App\HybridSession($this->db, $this->generateConfigMock());

        parent::setUp();
    }

    /**
     * Create a mock object of the Config class with the method 'get' mocked.
     * Define the return value of the method 'get' according to the key.
     * @return Config
     * @throws Exception
     */
    private function generateConfigMock(): Config
    {
        $config = $this->createMock(Config::class);
        $config->method('get')->willReturnCallback(function (string $key) {
            $array = [
                'session.lifetime' => 3600,
                'session.cookie.name' => 'session_id',
            ];

            if (!array_key_exists(strtolower($key), $array)) {
                return '';
            }

            return $array[$key];
        });

        return $config;
    }
}
