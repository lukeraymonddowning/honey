<?php


namespace Lukeraymonddowning\Honey\InputValues;


class Values
{

    public static function alpine()
    {
        return static::resolve('alpine');
    }

    public static function timeOfPageLoad()
    {
        return static::resolve('time_of_page_load');
    }

    protected static function resolve($input)
    {
        return app(config("honey.input_values.$input"));
    }

}