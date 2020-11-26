<?php


namespace Lukeraymonddowning\Honey\Checks;


use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;

class PresentButEmptyCheck implements Check
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

        if ($this->isFilled($data)) {
            return false;
        }

        return true;
    }

    protected function notInRequest($data)
    {
        return !array_key_exists($this->inputNameSelector->getPresentButEmptyInputName(), $data);
    }

    protected function isFilled($data)
    {
        return !empty($data[$this->inputNameSelector->getPresentButEmptyInputName()]);
    }
}