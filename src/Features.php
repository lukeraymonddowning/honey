<?php


namespace Lukeraymonddowning\Honey;


class Features
{
    protected static function enabled($feature)
    {
        return in_array($feature, config('honey.features'));
    }

    public static function spammerIpTracking()
    {
        return 'spammer-ip-tracking';
    }

    public static function spammerIpTrackingIsEnabled()
    {
        return static::enabled(static::spammerIpTracking());
    }

    public static function blockSpammersGlobally()
    {
        return 'block-spammers-globally';
    }

    public static function blockSpammersGloballyIsEnabled()
    {
        return static::enabled(static::blockSpammersGlobally());
    }
}