<?php


namespace Lukeraymonddowning\Honey\Http\Middleware;


use Illuminate\Http\Request;
use Lukeraymonddowning\Honey\Exceptions\RecaptchaFailedException;
use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;

class CheckRecaptchaToken
{

    public function handle(Request $request, callable $next)
    {
        if (!$token = $this->token($request)) {
            Honey::fail();
        }

        try {
            Honey::recaptcha()->checkToken($token);
        } catch (RecaptchaFailedException $exception) {
            report($exception);
            Honey::fail();
        }

        if (Honey::recaptcha()->isSpam()) {
            Honey::fail();
        }

        return $next($request);
    }

    protected function token(Request $request)
    {
        return $request->{app(InputNameSelector::class)->getRecaptchaInputName()} ?? null;
    }



}