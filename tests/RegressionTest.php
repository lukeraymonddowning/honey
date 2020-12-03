<?php


namespace Lukeraymonddowning\Honey\Tests;


use Lukeraymonddowning\Honey\Features;

class RegressionTest extends TestCase
{
    /** @test */
    public function if_the_features_config_is_not_available_the_feature_class_still_works()
    {
        $this->app['config']->set('honey.features', null);

        $this->assertFalse(Features::spammerIpTrackingIsEnabled());
    }
}