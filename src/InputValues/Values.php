<?php


namespace Lukeraymonddowning\Honey\InputValues;


class Values
{

    public static function alpine()
    {
        return static::resolve('alpine');
    }

    protected static function resolve($input)
    {
        return app(config("honey.input_values.$input"));
    }

}