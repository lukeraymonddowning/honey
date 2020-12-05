<?php


namespace Lukeraymonddowning\Honey\Http\Middleware;

use Illuminate\Http\Request;
use Lukeraymonddowning\Honey\Checks\UserIsBlockedSpammerCheck;
use Lukeraymonddowning\Honey\Facades\Honey;

class BlockSpammers
{

    public function handle(Request $request, callable $next)
    {
        if ($this->userIsAKnownSpammer()) {
            Honey::fail();
        }

        return $next($request);
    }

    protected function userIsAKnownSpammer()
    {
        return !app(UserIsBlockedSpammerCheck::class)->passes(request()->input());
    }

}