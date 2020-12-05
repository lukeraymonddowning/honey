<?php


namespace Lukeraymonddowning\Honey\Checks;


use Illuminate\Support\Facades\Request;
use Lukeraymonddowning\Honey\Features;
use Lukeraymonddowning\Honey\Models\Spammer;

class UserIsBlockedSpammerCheck implements Check
{
    public function passes($data)
    {
        if (!Features::spammerIpTrackingIsEnabled()) {
            return true;
        }

        return !Spammer::isBlocked(Request::ip());
    }
}