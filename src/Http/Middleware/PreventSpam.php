<?php


namespace Lukeraymonddowning\Honey\Http\Middleware;


use Illuminate\Http\Request;
use Lukeraymonddowning\Honey\Facades\Honey;

class PreventSpam
{

    public function handle(Request $request, callable $next)
    {
        if (!Honey::check($request->all())) {
            abort(422, "You shall not pass!");
        }

        return $next($request);
    }

}