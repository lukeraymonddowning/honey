<?php


namespace Lukeraymonddowning\Honey\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Honey
 * @package Lukeraymonddowning\Honey\Facades
 *
 * @method static bool check($data)
 * @method static failUsing(callable $function) Register an alternate callback when the middleware detects spam.
 * @method static beforeFailing(callable $hook) Register a callback that should be fired before the registered fail callback is fired.
 * @method static fail() Call the registered failure callback.
 * @method static setMinimumTimePassed($time) Set the minimum amount of time between a page being loaded and submitted.
 */
class Honey extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'honey';
    }

}