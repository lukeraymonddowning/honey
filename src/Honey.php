<?php


namespace Lukeraymonddowning\Honey;


use Illuminate\Support\Collection;

class Honey
{
    public static $minimumTimeInSeconds;
    protected static Collection $checks;

    public function __construct(Collection $checks)
    {
        static::$checks = $checks;
    }

    public static function setMinimumTimePassed($timeInSeconds)
    {
        static::$minimumTimeInSeconds = $timeInSeconds;
    }

    public function check($data)
    {
        return static::$checks->map->passes($data)->filter()->count() === static::$checks->count();
    }

}