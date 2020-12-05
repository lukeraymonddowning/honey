<?php


namespace Lukeraymonddowning\Honey\Views;

use Illuminate\View\Component;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;

class Hcaptcha extends Component
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
                <script src="https://hcaptcha.com/1/api.js?onload=honeyHcaptcha&render=explicit" async defer></script>
                <script>
                    function honeyHcaptcha() {
                        document.querySelectorAll('div[data-purpose="honey-rc"]').forEach(el => {
                            hcaptcha.render(el, { 
                                sitekey: '{{ $siteKey() }}',
                                callback: result => console.log(result) // TODO: We should update the hidden input with this token
                            })    
                        });
                    }
                </script>
            @endonce
            <div data-purpose="honey-rc"
                 {{ $attributes }}
            ></div>
            <input type="hidden" name="">
        blade;
    }

    public function siteKey()
    {
        return $this->siteKey ??= static::config()['site_key'];
    }

    protected static function config()
    {
        return config('honey.hcaptcha');
    }

}