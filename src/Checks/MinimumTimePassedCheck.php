<?php


namespace Lukeraymonddowning\Honey\Checks;


use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\InputValues\Values;

class MinimumTimePassedCheck implements Check
{
    public function passes($data)
    {
        $value = $data[Honey::inputs()->getTimeOfPageLoadInputName()];
        return rescue(fn() => Values::timeOfPageLoad()->checkValue($value));
    }
}