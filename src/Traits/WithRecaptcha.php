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

    public function mountWithRecaptcha()
    {
        $this->honeyInputs[Honey::inputs()->getRecaptchaInputName()] = null;
    }

    public function getRecaptchaPassedProperty()
    {
        return !Honey::recaptcha()->checkToken($this->honeyInputs[Honey::inputs()->getRecaptchaInputName()])->isSpam();
    }

    public function recaptchaPasses()
    {
        return $this->recaptchaPassed;
    }
}