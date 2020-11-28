<?php


namespace Lukeraymonddowning\Honey\Traits;


use Lukeraymonddowning\Honey\Honey;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\InputValues\Values;

trait HoneyForm
{
    public $honeyInputs = [];

    public function mountHoneyForm(InputNameSelector $inputNameSelector)
    {
        $this->honeyInputs[$inputNameSelector->getPresentButEmptyInputName()] = null;
        $this->honeyInputs[$inputNameSelector->getTimeOfPageLoadInputName()] = Values::timeOfPageLoad()->getValue();
        $this->honeyInputs[$inputNameSelector->getAlpineInputName()] = null;
    }

    protected function honey(): Honey
    {
        return app('honey');
    }

    protected function passesHoneyChecks()
    {
        return $this->honey()->check($this->honeyInputs);
    }
}