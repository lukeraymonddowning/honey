<?php


namespace Lukeraymonddowning\Honey\Tests;


use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\Http\Middleware\CheckRecaptchaToken;

class ManualDisableTest extends TestCase
{
    
    /** @test */
    public function it_can_be_disabled_manually()
    {
        $this->assertTrue(Honey::isEnabled());
        Honey::disable();
        $this->assertFalse(Honey::isEnabled());
    }

    /** @test */
    public function the_recaptcha_middleware_stack_completes_when_disabled()
    {
        Honey::disable();

        $this->expectExceptionObject(new \Exception("Hello world"));

        $middleware = new CheckRecaptchaToken();

        $request = request()->replace([Honey::inputs()->getRecaptchaInputName() => 'foobar']);

        $middleware->handle($request, function() {
            throw new \Exception("Hello world");
        });
    }
    
}