<?php


namespace Lukeraymonddowning\Honey;


use ArrayAccess;
use Illuminate\Support\Traits\ForwardsCalls;
use Lukeraymonddowning\Honey\Facades\Honey;

/**
 * Class RecaptchaResponse
 * @package Lukeraymonddowning\Honey
 *
 * @method boolean isSpam()
 */
class RecaptchaResponse implements ArrayAccess
{
    use ForwardsCalls;

    public $success, $score, $action, $challenge_ts, $hostname, $error_codes;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $this[$key] = $value;
        }
    }

    public function __call($name, $arguments)
    {
        return $this->forwardCallTo(Honey::recaptcha(), $name, $arguments);
    }

    public function offsetExists($offset)
    {
        if ($offset == "error-codes") {
            return true;
        }

        return isset($this->$offset);
    }

    public function offsetGet($offset)
    {
        if ($offset == "error-codes") {
            return $this->error_codes;
        }

        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {
        if ($offset == "error-codes") {
            return $this->error_codes = $value;
        }

        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        if ($offset == "error-codes") {
            return $this->error_codes = null;
        }

        $this->$offset = null;
    }
}