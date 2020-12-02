<?php


namespace Lukeraymonddowning\Honey\Checks;


use Exception;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\InputValues\Values;

class JavascriptInputFilledCheck implements Check
{
    private InputNameSelector $inputNameSelector;

    public function __construct(InputNameSelector $inputNameSelector)
    {
        $this->inputNameSelector = $inputNameSelector;
    }

    public function passes($data): bool
    {
        if ($this->notInRequest($data)) {
            return false;
        }

        if ($this->hasUnexpectedValue($data)) {
            return false;
        }

        return true;
    }

    protected function notInRequest($data)
    {
        return !array_key_exists($this->inputNameSelector->getJavascriptInputName(), $data);
    }

    protected function hasUnexpectedValue($data)
    {
        $value = $data[$this->inputNameSelector->getJavascriptInputName()];

        try {
            return !Values::javascript()->checkValue($value);
        } catch (Exception $exception) {
            return true;
        }
    }
}