<?php


namespace Lukeraymonddowning\Honey\InputValues;


interface InputValue
{

    public function getValue(): string;

    public function checkValue($value): bool;

}