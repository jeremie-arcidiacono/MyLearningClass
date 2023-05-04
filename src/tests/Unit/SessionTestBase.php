<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    January 2023
 * Description :    This abstract class is used to test any Session driver class (note that there isn't a session cookie check).
 ** * * * * * * * * * * * * * * * * * * * * * * */

namespace Tests\Unit;

use App\Contracts\ISession;
use PHPUnit\Framework\TestCase;

/**
 * Class SessionTest
 * Used to test any Session wrapper class (note that there isn't a session cookie check)
 * Create a class that extends this one and implement the setUp method to set the $session property to an instance of the class you want to
 * test.
 */
abstract class SessionTestBase extends TestCase
{
    protected ISession $session;

    /**
     * Start the session
     */
    public function setUp(): void
    {
        parent::setUp();

        // Start the session
        $this->session->start();
    }

    /**
     * Check if the session is active or not after a start, a destroy, a close, etc..
     * @return void
     * @throws \Exception
     */
    public function test_session_start_destroy_close()
    {
        $this->assertTrue($this->session->isActive());

        $this->session->destroy();

        $this->assertFalse($this->session->isActive());

        $this->session->start();

        $this->assertTrue($this->session->isActive());

        $this->session->close();

        $this->assertFalse($this->session->isActive());
    }

    /**
     * Test if the session id is regenerated and keep the same data
     * @return void
     */
    public function test_regenerate_id()
    {
        $id = $this->session->getId();
        $this->session->set('test', 'test');
        $this->session->setFlash('flash', 'flash');

        $this->session->regenerate();

        $this->assertTrue($this->session->isActive());
        $this->assertNotEquals($id, $this->session->getId());
        $this->assertSame('test', $this->session->get('test'));
        $this->assertSame('flash', $this->session->getFlash('flash'));
    }

    public function test_get_set_string()
    {
        $this->session->set('test', 'test');
        $this->assertSame('test', $this->session->get('test'));
    }

    public function test_get_set_array()
    {
        $this->session->set('test', ['test', 'test2']);
        $this->assertSame(['test', 'test2'], $this->session->get('test'));
    }

    public function test_get_set_null()
    {
        $this->session->set('test', null);
        $this->assertNull($this->session->get('test'));
    }

    public function test_has_string()
    {
        $this->session->set('test', 'test');
        $this->assertTrue($this->session->has('test'));
    }

    public function test_has_array()
    {
        $this->session->set('test', ['test', 'test2']);
        $this->assertTrue($this->session->has('test'));
    }

    public function test_has_null()
    {
        $this->session->set('test', null);
        $this->assertFalse($this->session->has('test'));
    }

    public function test_remove()
    {
        $this->session->set('test', 'test');
        $this->session->remove('test');
        $this->assertFalse($this->session->has('test'));
    }

    public function test_flash_string()
    {
        $this->session->setFlash('test', 'test');
        $this->assertTrue($this->session->hasFlash('test'));
        $this->assertSame('test', $this->session->getFlash('test'));
        $this->assertFalse($this->session->hasFlash('test'));
    }

    public function test_flash_array()
    {
        $this->session->setFlash('test', ['test', 'test2']);
        $this->assertTrue($this->session->hasFlash('test'));
        $this->assertSame(['test', 'test2'], $this->session->getFlash('test'));
        $this->assertFalse($this->session->hasFlash('test'));
    }

    public function test_flash_null()
    {
        $this->expectException(\TypeError::class);
        $this->session->setFlash('test', null);
        $this->assertFalse($this->session->hasFlash('test'));
    }

    public function test_get_default_value()
    {
        $this->assertSame('test', $this->session->get('test', 'test'));
        $this->assertSame(['test'], $this->session->get('test', ['test']));
        $this->assertNull($this->session->get('test', null));

        $this->assertSame('test', $this->session->getFlash('test', 'test'));
        $this->assertSame(['test'], $this->session->getFlash('test', ['test']));
        $this->assertNull($this->session->getFlash('test', null));
    }
}
