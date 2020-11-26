<?php


namespace Lukeraymonddowning\Honey\Tests;


use Illuminate\Support\Facades\Blade;
use Lukeraymonddowning\Honey\Views\Honey;

class BladeComponentTest extends TestCase
{

    /** @test */
    public function it_registers_a_blade_component_for_honey()
    {
        $components = Blade::getClassComponentAliases();
        $this->assertEquals(Honey::class, $components['honey']);
    }

}