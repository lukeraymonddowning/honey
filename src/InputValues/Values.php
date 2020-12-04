<?php


namespace Lukeraymonddowning\Honey\InputValues;


class Values
{

    public static function javascript()
    {
        return static::resolve('javascript');
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