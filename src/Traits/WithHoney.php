<?php


namespace Lukeraymonddowning\Honey\Traits;


use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\InputValues\Values;

/**
 * Trait WithHoney
 * @package Lukeraymonddowning\Honey\Traits
 *
 * @property boolean honeyPassed
 */
trait WithHoney
{
    public $honeyInputs = [];

    public function mountWithHoney()
    {
        $this->honeyInputs[Honey::inputs()->getPresentButEmptyInputName()] = null;
        $this->honeyInputs[Honey::inputs()->getTimeOfPageLoadInputName()] = Values::timeOfPageLoad()->getValue();
        $this->honeyInputs[Honey::inputs()->getAlpineInputName()] = null;
    }

    public function getHoneyPassedProperty()
    {
        if (!in_array(WithRecaptcha::class, class_uses_recursive(static::class))) {
            return Honey::check($this->honeyInputs);
        }

        return Honey::check($this->honeyInputs) && $this->recaptchaPasses();
    }

    public function honeyPasses()
    {
        return $this->honeyPassed;
    }
}