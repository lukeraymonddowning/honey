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
                            grecaptcha.execute('{{ $siteKey() }}', { action }).then(token => {
                                el.value = token;
                                el.dispatchEvent(new Event('change'));
                            });
                        },
                        recaptchaInputs() {
                            return document.querySelectorAll('input[data-purpose="honey-rc"]');
                        },
                        refreshAllTokens() {
                            this.recaptchaInputs().forEach(input => window.Honey.recaptcha(input, input.dataset.action));
                        },
                    };
                    
                    window.addEventListener('load', () => {
                        grecaptcha.ready(() => {
                           window.Honey.refreshAllTokens();
                           setInterval(() => window.Honey.refreshAllTokens(), {{ $tokenRefreshInterval() }})
                        })    
                    });
                    
                    document.addEventListener('livewire:load', function () {
                        Livewire.hook('message.received', (message, component) => {
                            if (!component.el.querySelector('input[data-purpose="honey-rc"]')) return;
                            window.Honey.refreshAllTokens();
                        });
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