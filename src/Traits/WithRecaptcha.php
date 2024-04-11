<?php


namespace Lukeraymonddowning\Honey\Traits;


use Lukeraymonddowning\Honey\Facades\Honey;

/**
 * @property boolean recaptchaPassed
 */
trait WithRecaptcha
{
    public $honeyInputs = [];

    public function initializeWithRecaptcha()
    {
        Honey::recaptcha()->afterRequesting(fn() => $this->requestRecaptchaTokenRefresh());
    }

    public function requestRecaptchaTokenRefresh()
    {
        $this->dispatch('recaptcha-refresh-required');
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

    public function recaptchaPasses()
    {
        return $this->recaptchaPassed;
    }
}