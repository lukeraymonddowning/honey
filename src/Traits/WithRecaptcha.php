<?php


namespace Lukeraymonddowning\Honey\Traits;


use Lukeraymonddowning\Honey\Facades\Honey;

/**
 * Trait WithRecaptcha
 * @package Lukeraymonddowning\Honey\Traits
 *
 * @property boolean recaptchaPassed
 */
trait WithRecaptcha
{
    public $honeyInputs = [];

    public function initializeWithRecaptcha()
    {
        Honey::recaptcha()->afterRequesting(fn() => $this->requestRecaptchaTokenRefresh());
    }

    public function mountWithRecaptcha()
    {
        $this->honeyInputs[Honey::inputs()->getRecaptchaInputName()] = null;
    }

    public function getRecaptchaPassedProperty()
    {
        $response = !Honey::recaptcha()->checkToken($this->recaptchaToken())->isSpam();
        return $response;
    }

    protected function recaptchaToken()
    {
        return $this->honeyInputs[Honey::inputs()->getRecaptchaInputName()];
    }

    public function requestRecaptchaTokenRefresh()
    {
        $this->dispatchBrowserEvent('recaptcha-refresh-required');
    }

    public function recaptchaPasses()
    {
        return $this->recaptchaPassed;
    }
}