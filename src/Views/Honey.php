<?php


namespace Lukeraymonddowning\Honey\Views;


use Illuminate\View\Component;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;

class Honey extends Component
{
    protected InputNameSelector $inputNameSelector;

    public function __construct(InputNameSelector $inputNameSelector)
    {
        $this->inputNameSelector = $inputNameSelector;
    }

    public function render()
    {
        return <<<'blade'
            <div style="display: none;">
                <input type="text" name="{{ $inputNameSelector->getPresentButEmptyInputName() }}" value="">
                <input type="text" name="{{ $inputNameSelector->getTimeOfPageLoadInputName() }}" value="{{ microtime() }}">
            </div>     
        blade;
    }
}