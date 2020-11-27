<?php


namespace Lukeraymonddowning\Honey\Tests;


use Lukeraymonddowning\Honey\Facades\Honey;

class EnvironmentDisableTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('app.env', 'local');
    }

    /** @test */
    public function it_is_only_enabled_in_specified_environments()
    {
        // If Honey is not disabled, it will throw an exception here
        Honey::fail();
        $this->expectNotToPerformAssertions();
    }
    
    /** @test */
    public function check_will_always_return_true_when_disabled()
    {
        $this->assertTrue(Honey::check([]));
    }

}