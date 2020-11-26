<?php


namespace Lukeraymonddowning\Honey\Checks;


use Exception;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\InputValues\Values;

class MinimumTimePassedCheck implements Check
{
    private InputNameSelector $inputNameSelector;

    public function __construct(InputNameSelector $inputNameSelector)
    {
        $this->inputNameSelector = $inputNameSelector;
    }

    public function passes($data): bool
    {
        try {
            return Values::timeOfPageLoad()->checkValue($data[$this->inputNameSelector->getTimeOfPageLoadInputName()]);
        } catch (Exception $exception) {
            return false;
        }
    }
}