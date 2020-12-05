<?php


namespace Lukeraymonddowning\Honey\InputValues;


use Illuminate\Support\Facades\Crypt;

class TimeOfPageLoadValue implements InputValue
{
    public function getValue(): string
    {
        return Crypt::encrypt(microtime(true));
    }

    public function checkValue($value): bool
    {
        return microtime(true) - Crypt::decrypt($value) >= static::getConfiguredMinimumTime();
    }

    protected static function getConfiguredMinimumTime()
    {
        return config('honey.minimum_time_passed', 3);
    }
}