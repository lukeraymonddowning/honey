<?php


namespace Lukeraymonddowning\Honey\InputNameSelectors;


interface InputNameSelector
{
    public function getPresentButEmptyInputName(): string;

    public function getTimeOfPageLoadInputName(): string;

    public function getAlpineInputName(): string;

    public function getRecaptchaInputName(): string;
}