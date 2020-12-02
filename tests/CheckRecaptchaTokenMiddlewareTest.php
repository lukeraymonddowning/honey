<?php


namespace Lukeraymonddowning\Honey\Tests;


use Illuminate\Support\Facades\Http;
use Lukeraymonddowning\Honey\Exceptions\RecaptchaFailedException;
use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\Http\Middleware\CheckRecaptchaToken;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckRecaptchaTokenMiddlewareTest extends TestCase
{

    /** @test */
    public function it_submits_the_token_to_recaptcha_and_ensures_the_score_meets_the_minimum()
    {
        Http::fake(['*' => ['score' => 0.8]]);

        request()->merge(['honey_recaptcha_token' => 'foobar']);
        (new CheckRecaptchaToken())->handle(
            request(),
            function () {
                $this->assertEquals(0.8, Honey::recaptcha()->response()['score']);
            }
        );
    }

    /** @test */
    public function it_fails_if_the_score_is_below_the_minimum()
    {
        Http::fake(['*' => ['score' => 0.1]]);

        request()->merge(['honey_recapture_token' => 'foobar']);
        try {
            (new CheckRecaptchaToken())->handle(
                request(),
                function () {
                    $this->fail("The request should have been aborted.");
                }
            );
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /** @test */
    public function it_fails_if_the_token_is_missing()
    {
        Http::fake(['*' => ['score' => 0.1]]);

        try {
            (new CheckRecaptchaToken())->handle(
                request(),
                function () {
                    $this->fail("The request should have been aborted.");
                }
            );
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /** @test */
    public function it_fails_if_an_exception_is_thrown()
    {
        Http::fake(
            [
                '*' => function () {
                    throw new RecaptchaFailedException(['bad-code']);
                }
            ]
        );

        request()->merge(['honey_recapture_token' => 'foobar']);
        try {
            (new CheckRecaptchaToken())->handle(
                request(),
                function () {
                    $this->fail("The request should have been aborted.");
                }
            );
        } catch
        (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

}