<?php


namespace Lukeraymonddowning\Honey\Checks;


use Exception;
use Illuminate\Support\Facades\Crypt;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;

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
            return $this->getTimePassed($data) >= $this->getConfiguredMinimumTime();
        } catch (Exception $exception) {
            return false;
        }
    }

    protected function getTimePassed($data)
    {
        return microtime(true) - $this->getDecryptedValue($data);
    }

    protected function getDecryptedValue($data)
    {
        return Crypt::decrypt($data[$this->inputNameSelector->getTimeOfPageLoadInputName()]);
    }

    protected function getConfiguredMinimumTime()
    {
        return config('honey.minimum_time_passed');
    }
}