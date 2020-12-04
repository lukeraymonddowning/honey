<?php


namespace Lukeraymonddowning\Honey\InputNameSelectors;


class StaticInputNameSelector implements InputNameSelector
{
    protected $inputNames;

    public function __construct(array $inputNames)
    {
        $this->inputNames = $inputNames;
    }

    public function getPresentButEmptyInputName(): string
    {
        return $this->inputNames['present_but_empty'];
    }

    public function getTimeOfPageLoadInputName(): string
    {
        return $this->inputNames['time_of_page_load'];
    }

    public function getJavascriptInputName(): string
    {
        return $this->inputNames['javascript_input'];
    }

    public function getRecaptchaInputName(): string
    {
        return $this->inputNames['recaptcha_input'];
    }
}