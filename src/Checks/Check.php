<?php


namespace Lukeraymonddowning\Honey\Checks;


interface Check
{

    public function passes($data): bool;

}