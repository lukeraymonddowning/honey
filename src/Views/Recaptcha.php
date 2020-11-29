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
                <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey() }}"></script>
                <script>
                    window.Honey = {
                        recaptcha(el, action = 'submit') {
                            grecaptcha.execute('{{ $siteKey() }}', { action }).then(token => {
                                el.value = token;
                                el.dispatchEvent(new Event('change'));
                           })
                        },
                    };
                </script>
            @endonce
            <input wire:model.lazy.defer="honeyInputs.{{ $inputName }}"
                   x-data="" 
                   x-init="
                   grecaptcha.ready(() => {
                       window.Honey.recaptcha($el, '{{ $attributes['action'] ?? 'submit' }}');
                       setInterval(() => {
                           window.Honey.recaptcha($el, '{{ $attributes['action'] ?? 'submit' }}');
                       }, {{ $tokenRefreshInterval() }})
                   })"
                   {{ $attributes }}
                   type="hidden" 
                   name="{{ $inputName }}">
        blade;
    }

    public function siteKey()
    {
        return $this->siteKey ??= static::config()['site_key'];
    }

    public function tokenRefreshInterval()
    {
        return static::config()['token_refresh_interval'];
    }

    protected static function config()
    {
        return config('honey.recaptcha');
    }

}