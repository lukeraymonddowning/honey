<?php


namespace Lukeraymonddowning\Honey\Checks;

use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\InputValues\Values;

class JavascriptInputFilledCheck implements Check
{
    protected $data;

    public function passes($data)
    {
        $this->data = collect($data);

        if ($this->missingFromData()) {
            return false;
        }

        return $this->hasExpectedValue();
    }

    protected function missingFromData()
    {
        return !$this->data->offsetExists(Honey::inputs()->getJavascriptInputName());
    }

    protected function hasExpectedValue()
    {
        $value = $this->data[Honey::inputs()->getJavascriptInputName()];
        return rescue(fn() => Values::javascript()->checkValue($value));
    }
}