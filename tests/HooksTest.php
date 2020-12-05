<?php


namespace Lukeraymonddowning\Honey\Tests;


use Exception;
use Illuminate\Support\Facades\Http;
use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\RecaptchaResponse;

class HooksTest extends TestCase
{
    /** @test */
    public function it_can_register_a_hook_after_making_a_captcha_token_request()
    {
        Http::fake(['*' => [
            'success' => true,
            'score' => 0.8,
            'action' => 'submit',
            'challenge_ts' => now()->toIso8601String(),
            'hostname' => config('app.url'),
            'error-codes' => []
        ]]);

        $this->expectException(Exception::class);
        Honey::recaptcha()->afterRequesting(function($response) { $this->assertInstanceOf(RecaptchaResponse::class, $response); });
        Honey::recaptcha()->afterRequesting(function() { throw new Exception("We should get to here"); });

        Honey::recaptcha()->checkToken('foobar');
    }
}