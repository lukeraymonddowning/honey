<?php


namespace Lukeraymonddowning\Honey\Checks;


use Exception;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\InputValues\Values;

class AlpineInputFilledCheck implements Check
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
        return !array_key_exists($this->inputNameSelector->getAlpineInputName(), $data);
    }

    protected function hasUnexpectedValue($data)
    {
        $value = $data[$this->inputNameSelector->getAlpineInputName()];

        try {
            return !Values::alpine()->checkValue($value);
        } catch (Exception $exception) {
            return true;
        }
    }
}