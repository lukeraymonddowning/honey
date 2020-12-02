<?php


namespace Lukeraymonddowning\Honey\Views;

use Illuminate\View\Component;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;

class Recaptcha extends Component
{
    public $inputName;

    public function __construct(InputNameSelector $inputNameSelector)
    {
        $this->inputName = $inputNameSelector->getRecaptchaInputName();
    }

    public function render(callable $callback = null)
    {
        return <<<'blade'
            @once
                <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey() }}" defer></script>
                <script>
                    window.Honey = {
                        recaptcha(el, action = 'submit') {
                            console.log(el, action);
                            grecaptcha.execute('{{ $siteKey() }}', { action }).then(token => {
                                el.value = token;
                                el.dispatchEvent(new Event('change'));
                           })
                        },
                    };
                    
                    window.addEventListener('load', () => {
                        grecaptcha.ready(() => {
                           recaptchaInputs = document.querySelectorAll('input[data-purpose="honey-rc"]');
                           recaptchaInputs.forEach(input => window.Honey.recaptcha(input, input.dataset.action));
                           
                           setInterval(() => {
                                recaptchaInputs.forEach(input => window.Honey.recaptcha(input, input.dataset.action));
                           }, {{ $tokenRefreshInterval() }})
                        })    
                    });
                </script>
            @endonce
            <input wire:model.lazy.defer="honeyInputs.{{ $inputName }}"
                   {{ $attributes }}
                   type="hidden" 
                   data-purpose="honey-rc"
                   data-action="{{ $attributes['action'] ?? 'submit' }}"
                   name="{{ $inputName }}">
        blade;
    }

    public function siteKey()
    {
        return $this->siteKey ??= static::config()['site_key'];
    }

    protected static function config()
    {
        return config('honey.recaptcha');
    }

    public function tokenRefreshInterval()
    {
        return static::config()['token_refresh_interval'];
    }

}