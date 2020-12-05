<?php


namespace Lukeraymonddowning\Honey\Http\Middleware;

use Illuminate\Http\Request;
use Lukeraymonddowning\Honey\Facades\Honey;

class CheckRecaptchaToken
{
    protected $request, $token;

    public function handle(Request $request, callable $next)
    {
        $this->request = $request;

        collect($this->reasonsToFail())->filter()->whenNotEmpty(fn() => Honey::fail());

        return $next($request);
    }

    protected function reasonsToFail()
    {
        return [
            empty($this->token()),
            empty(rescue(fn() => Honey::recaptcha()->checkToken($this->token()))),
            rescue(fn() => Honey::recaptcha()->isSpam(), true)
        ];
    }

    protected function token()
    {
        return $this->token ??= $this->request->{Honey::inputs()->getRecaptchaInputName()};
    }

}