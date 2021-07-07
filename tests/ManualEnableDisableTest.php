<?php


namespace Lukeraymonddowning\Honey\Tests;


use Symfony\Component\HttpKernel\Exception\HttpException;
use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\Http\Middleware\CheckRecaptchaToken;


class ManualEnableDisableTest extends TestCase
{

    /** @test */
    public function it_can_be_disabled_and_enabled_manually()
    {
        $this->assertTrue(Honey::isEnabled());
        Honey::disable();
        $this->assertFalse(Honey::isEnabled());
        Honey::enable();
        $this->assertTrue(Honey::isEnabled());
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

    /** @test */
    public function the_recaptcha_middleware_stack_does_not_complete_when_enabled_again()
    {
        Honey::disable();
        Honey::enable();

        $this->expectExceptionObject(new HTTPException(422, 'You shall not pass!'));

        $middleware = new CheckRecaptchaToken();

        $request = request()->replace([Honey::inputs()->getRecaptchaInputName() => 'foobar']);

        $middleware->handle($request, function() {});
    }

}
