<?php


namespace Lukeraymonddowning\Honey\Checks;


use Lukeraymonddowning\Honey\Facades\Honey;

class PresentButEmptyCheck implements Check
{
    protected $data;

    public function passes($data)
    {
        $this->data = collect($data);

        if ($this->missingFromData()) {
            return false;
        }

        if ($this->isFilled()) {
            return false;
        }

        return true;
    }

    protected function missingFromData()
    {
        return !$this->data->has(Honey::inputs()->getPresentButEmptyInputName());
    }

    protected function isFilled()
    {
        return !empty($this->data[Honey::inputs()->getPresentButEmptyInputName()]);
    }
}