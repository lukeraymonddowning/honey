<?php


namespace Lukeraymonddowning\Honey\Exceptions;


use Exception;

class RecaptchaFailedException extends Exception
{
    public function __construct($errorCodes)
    {
        $errors = collect($errorCodes)->join(", ", " and ");
        parent::__construct("The following errors were returned from the recaptcha token: $errors");
    }
}