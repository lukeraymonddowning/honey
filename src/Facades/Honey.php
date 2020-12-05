<?php


namespace Lukeraymonddowning\Honey\Facades;


use Illuminate\Support\Facades\Facade;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\Captcha\Recaptcha;

/**
 * @method static bool check($data)
 * @method static InputNameSelector inputs()
 * @method static fail() Call the registered failure callback.
 * @method static Recaptcha recaptcha() Resolve the Recaptcha object out of the container.
 * @method static failUsing(callable $function) Register an alternate callback when the middleware detects spam.
 * @method static setMinimumTimePassed($time) Set the minimum amount of time between a page being loaded and submitted.
 * @method static beforeFailing(callable $hook) Register a callback that should be fired before the registered fail callback is fired.
 *
 * @see \Lukeraymonddowning\Honey\Honey
 */
class Honey extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'honey';
    }
}