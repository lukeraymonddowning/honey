<?php


namespace Lukeraymonddowning\Honey\Checks;


use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\InputValues\Values;

class MinimumTimePassedCheck implements Check
{
    protected $data;

    protected function missingFromData()
    {
        return !$this->data->offsetExists(Honey::inputs()->getTimeOfPageLoadInputName());
    }

    public function passes($data)
    {
        $this->data = collect($data);

        if ($this->missingFromData()) {
            return false;
        }

        $value = $data[Honey::inputs()->getTimeOfPageLoadInputName()];
        return rescue(fn() => Values::timeOfPageLoad()->checkValue($value));
    }
}