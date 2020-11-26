<?php


namespace Lukeraymonddowning\Honey;


use Illuminate\Support\Collection;

class Honey
{
    protected static Collection $checks;
    protected static $failUsing;

    public function __construct(Collection $checks, callable $failUsing)
    {
        static::$checks = $checks;
        static::$failUsing = $failUsing;
    }

    public function check($data)
    {
        return static::$checks->map->passes($data)->filter()->count() === static::$checks->count();
    }

    public static function failUsing(callable $function)
    {
        static::$failUsing = $function;
    }

    public static function fail()
    {
        return app()->call(static::$failUsing);
    }

}