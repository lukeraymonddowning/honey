<?php


namespace Lukeraymonddowning\Honey\Http\Middleware;

use Illuminate\Http\Request;
use Lukeraymonddowning\Honey\Facades\Honey;

class PreventSpam
{

    public function handle(Request $request, callable $next)
    {
        $method = $request->method();
 
        if ($request->isMethod('post')) {
            return Honey::check($request->all()) ? $next($request) : Honey::fail();
        }
    }

}
