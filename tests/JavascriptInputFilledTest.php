<?php


namespace Lukeraymonddowning\Honey\Tests;


use Lukeraymonddowning\Honey\Checks\JavascriptInputFilledCheck;
use Lukeraymonddowning\Honey\InputValues\Values;

class JavascriptInputFilledTest extends TestCase
{

    /** @test */
    public function it_requires_the_exact_defined_input()
    {
        $check = app(JavascriptInputFilledCheck::class);

        $this->assertTrue($check->passes(['honey_javascript' => Values::javascript()->getValue()]));
        $this->assertEmpty($check->passes(['honey_javascript' => 'foobar']));
        $this->assertEmpty($check->passes(['honey_javascript' => '']));
        $this->assertEmpty($check->passes([]));
    }
    
}