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
                @once
                    <script>
                        window.addEventListener('load', () => {
                            setTimeout(() => {
                                document.querySelectorAll('input[data-purpose="{{ $inputNameSelector->getJavascriptInputName() }}"]')
                                    .forEach(input => {
                                        if (input.value.length > 0) {
                                            return;
                                        }
                                        
                                        input.value = "{{ $javascriptValue() }}";
                                        input.dispatchEvent(new Event('change'));
                                    });
                            }, {{ $javascriptTimeout() }})
                        });
                    </script>
                @endonce
                <div style="display: @isset($attributes['debug']) block @else none @endisset;">
                    <input wire:model.lazy.defer="honeyInputs.{{ $inputNameSelector->getPresentButEmptyInputName() }}" name="{{ $inputNameSelector->getPresentButEmptyInputName() }}" value="">
                    <input wire:model.lazy.defer="honeyInputs.{{ $inputNameSelector->getTimeOfPageLoadInputName() }}" name="{{ $inputNameSelector->getTimeOfPageLoadInputName() }}" value="{{ $timeOfPageLoadValue() }}">
                    <input wire:model.lazy.defer="honeyInputs.{{ $inputNameSelector->getJavascriptInputName() }}" data-purpose="{{ $inputNameSelector->getJavascriptInputName() }}" name="{{ $inputNameSelector->getJavascriptInputName() }}" value="">
                    {{ $slot }}
                </div>     
                @isset($attributes['recaptcha'])
                    <x-honey-recaptcha :action="$attributes['recaptcha'] === true ? 'submit' : $attributes['recaptcha']"/>
                @endisset
            blade;
    }

    public function timeOfPageLoadValue()
    {
        return Values::timeOfPageLoad()->getValue();
    }

    public function javascriptValue()
    {
        return Values::javascript()->getValue();
    }

    public function javascriptTimeout()
    {
        return config('honey.minimum_time_passed') * 1000;
    }
}