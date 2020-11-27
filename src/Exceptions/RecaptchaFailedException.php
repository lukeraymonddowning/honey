<?php


namespace Lukeraymonddowning\Honey\Exceptions;


use Exception;

class RecaptchaFailedException extends Exception
{
    public function __construct($errorCodes)
    {
        parent::__construct(
            "The following errors were thrown when trying to resolve the recaptcha token: "
            . collect($errorCodes)->join(", ", " and "),
            0,
            null
        );
    }
}