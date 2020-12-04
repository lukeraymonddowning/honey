<?php


namespace Lukeraymonddowning\Honey\InputValues;


use Illuminate\Support\Facades\Crypt;

class JavascriptInputValue implements InputValue
{
    public function getValue(): string
    {
        return Crypt::encrypt("Honey");
    }

    public function checkValue($value): bool
    {
        return Crypt::decrypt($value) == "Honey";
    }
}