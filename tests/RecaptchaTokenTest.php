<?php


namespace Lukeraymonddowning\Honey\Tests;


use Illuminate\Support\Facades\Http;
use Lukeraymonddowning\Honey\Exceptions\RecaptchaFailedException;
use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\Captcha\RecaptchaResponse;

class RecaptchaTokenTest extends TestCase
{

    /** @test */
    public function it_can_return_a_score_based_on_the_given_token()
    {
        Http::fake(['*' => [
            'success' => true,
            'score' => 0.8,
            'action' => 'submit',
            'challenge_ts' => now()->toIso8601String(),
            'hostname' => config('app.url'),
            'error-codes' => []
        ]]);

        $this->assertEquals(0.8, Honey::recaptcha()->checkToken('foobar')['score']);
        $this->assertInstanceOf(RecaptchaResponse::class, Honey::recaptcha()->checkToken('foobar'));
    }

    /** @test */
    public function it_can_chain_recaptcha_methods_after_the_check()
    {
        Http::fake(['*' => [
            'success' => true,
            'score' => 0.2,
            'action' => 'submit',
            'challenge_ts' => now()->toIso8601String(),
            'hostname' => config('app.url'),
            'error-codes' => []
        ]]);

        $this->assertTrue(Honey::recaptcha()->checkToken('foobar')->isSpam());
        $this->assertInstanceOf(RecaptchaResponse::class, Honey::recaptcha()->checkToken('foobar')->response());
    }

    /** @test */
    public function it_throws_an_exception_with_the_error_codes_if_it_fails()
    {
        Http::fake(['*' => [
            'success' => false,
            'score' => 0.8,
            'action' => 'submit',
            'challenge_ts' => now()->toIso8601String(),
            'hostname' => config('app.url'),
            'error-codes' => [
                'missing-input-secret',
                'bad-request'
            ]
        ]]);

        $this->expectException(RecaptchaFailedException::class);

        Honey::recaptcha()->checkToken('bad-token');
    }

    /** @test */
    public function you_can_call_the_check_token_as_many_times_as_you_want()
    {
        Http::fake(['*' => [
            'success' => true,
            'score' => 0.8,
            'action' => 'submit',
            'challenge_ts' => now()->toIso8601String(),
            'hostname' => config('app.url'),
            'error-codes' => []
        ]]);

        Honey::recaptcha()->checkToken('foobar');
        Honey::recaptcha()->checkToken('foobar');
        Honey::recaptcha()->checkToken('foobar');
        Honey::recaptcha()->checkToken('foobar');

        Http::assertSentCount(1);
    }

}