<?php


namespace Lukeraymonddowning\Honey\Views;


use Illuminate\View\Component;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\InputValues\Values;

class Honey extends Component
{
    public InputNameSelector $inputNameSelector;

    public function __construct(InputNameSelector $inputNameSelector)
    {
        $this->inputNameSelector = $inputNameSelector;
    }

    public function render()
    {
        return <<<'blade'
                <div style="display: {{ isset($attributes['debug']) ? 'block' : 'none' }};">
                    <input type="text" name="{{ $inputNameSelector->getPresentButEmptyInputName() }}" value="">
                    <input type="text" name="{{ $inputNameSelector->getTimeOfPageLoadInputName() }}" value="{{ $timeOfPageLoadValue() }}">
                    <input x-data="" x-init="setTimeout(function() {if ($el.value.length == 0) $el.value = '{{ $alpineValue() }}'}, {{ $alpineTimeout() }})" type="text" name="{{ $inputNameSelector->getAlpineInputName() }}" value="">
                    {{ $slot }}
                </div>     
            blade;
    }

    public function timeOfPageLoadValue()
    {
        return Values::timeOfPageLoad()->getValue();
    }

    public function alpineValue()
    {
        return Values::alpine()->getValue();
    }

    public function alpineTimeout()
    {
        return config('honey.minimum_time_passed') * 1000;
    }
}