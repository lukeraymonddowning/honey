<?php


namespace Lukeraymonddowning\Honey\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Honey
 * @package Lukeraymonddowning\Honey\Facades
 *
 * @method static bool check($data)
 * @method static setMinimumTimePassed($time) Set the minimum amount of time between a page being loaded and submitted.
 */
class Honey extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'honey';
    }

}