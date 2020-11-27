<?php


namespace Lukeraymonddowning\Honey\Tests;


use Lukeraymonddowning\Honey\Facades\Honey;

class ManualDisableTest extends TestCase
{
    
    /** @test */
    public function it_can_be_disabled_manually()
    {
        $this->assertTrue(Honey::isEnabled());
        Honey::disable();
        $this->assertFalse(Honey::isEnabled());
    }
    
}