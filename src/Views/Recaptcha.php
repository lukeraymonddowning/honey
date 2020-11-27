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
            @endonce
            <input x-data="" 
                   x-init="$el.form.onsubmit = (e) => { e.preventDefault(); grecaptcha.ready(() => {
                        grecaptcha.execute('{{ $siteKey() }}', { action: 'submit' }).then(token => {
                            $el.value = token;
                            @isset($attributes['x-callback'])
                                {{ $attributes['x-callback'] }}
                            @else
                                $el.form.submit();
                            @endisset
                        })
                   })}"
                   {{ $attributes }}
                   type="hidden" 
                   name="{{ $inputName }}">
        blade;
    }

    public function siteKey()
    {
        return $this->siteKey ??= config('honey.recaptcha.site_key');
    }

}