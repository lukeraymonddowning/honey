<?php


namespace Lukeraymonddowning\Honey\Captcha;


use Illuminate\Support\Facades\Http;
use Lukeraymonddowning\Honey\Exceptions\RecaptchaFailedException;

class Recaptcha
{
    const URL = "https://www.google.com/recaptcha/api/siteverify";
    protected RecaptchaResponse $response;
    protected $hooks = ['afterRequesting' => []];

    public function checkToken($token, $ip = null)
    {
        $this->response ??= new RecaptchaResponse(static::getResponse($token, $ip));

        collect($this->hooks['afterRequesting'])->each->__invoke($this->response);

        return $this->response;
    }

    protected static function getResponse($token, $ip = null)
    {
        $response = Http::asForm()->post(
            static::URL,
            [
                'secret' => static::secret(),
                'response' => $token,
                'remoteip' => $ip
            ]
        );

        if (collect($response['error-codes'] ?? [])->isNotEmpty()) {
            throw new RecaptchaFailedException($response['error-codes']);
        }

        return $response->json();
    }

    protected static function secret()
    {
        return config('honey.recaptcha.secret_key');
    }

    public function afterRequesting(callable $hook)
    {
        $this->hooks['afterRequesting'][] = $hook;
    }

    public function isSpam()
    {
        return $this->response()['score'] < static::minimumScore();
    }

    public function response()
    {
        return $this->response;
    }

    protected static function minimumScore()
    {
        return config('honey.recaptcha.minimum_score');
    }
}