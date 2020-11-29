<?php


namespace Lukeraymonddowning\Honey\Traits;


use Lukeraymonddowning\Honey\Honey;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\InputValues\Values;

trait HoneyForm
{
    public $honeyInputs = [];

    public function mountHoneyForm()
    {
        $this->honeyInputs[static::inputs()->getPresentButEmptyInputName()] = null;
        $this->honeyInputs[static::inputs()->getTimeOfPageLoadInputName()] = Values::timeOfPageLoad()->getValue();
        $this->honeyInputs[static::inputs()->getAlpineInputName()] = null;
        $this->honeyInputs[static::inputs()->getRecaptchaInputName()] = null;
    }

    protected function honey(): Honey
    {
        return app('honey');
    }

    protected function passesHoneyChecks()
    {
        return $this->honey()->check($this->honeyInputs);
    }

    protected function recaptcha()
    {
        return $this->honey()
            ->recaptcha()
            ->checkToken($this->honeyInputs[static::inputs()->getRecaptchaInputName()]);
    }

    protected static function inputs(): InputNameSelector
    {
        return app(InputNameSelector::class);
    }
}