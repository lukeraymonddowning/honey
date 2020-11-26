<?php


namespace Lukeraymonddowning\Honey\Http\Middleware;


use Illuminate\Http\Request;
use Lukeraymonddowning\Honey\Checks\UserIsBlockedSpammerCheck;
use Lukeraymonddowning\Honey\Facades\Honey;

class BlockSpammers
{

    public function handle(Request $request, callable $next)
    {
        if (!app(UserIsBlockedSpammerCheck::class)->passes($request->input())) {
            Honey::fail();
        }

        return $next($request);
    }

}