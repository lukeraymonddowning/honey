<?php


namespace Lukeraymonddowning\Honey\InputValues;


use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class JavascriptInputValue implements InputValue
{
    public function getValue(): string
    {
        return Crypt::encrypt("Honey");
    }

    public function checkValue($value): bool
    {
        try {
            return Crypt::decrypt($value) == "Honey";
        } catch (DecryptException) {
            return false;
        }
    }
}
