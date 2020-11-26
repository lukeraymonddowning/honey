<?php


namespace Lukeraymonddowning\Honey\Tests;


use Lukeraymonddowning\Honey\Checks\AlpineInputFilledCheck;
use Lukeraymonddowning\Honey\InputValues\Values;

class AlpineInputFilledCheckTest extends TestCase
{

    /** @test */
    public function it_requires_the_exact_defined_input()
    {
        $check = app(AlpineInputFilledCheck::class);

        $this->assertTrue($check->passes(['honey_alpine' => Values::alpine()->getValue()]));
        $this->assertFalse($check->passes(['honey_alpine' => 'foobar']));
        $this->assertFalse($check->passes(['honey_alpine' => '']));
        $this->assertFalse($check->passes([]));
    }
    
}