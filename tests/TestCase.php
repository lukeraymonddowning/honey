<?php

namespace Lukeraymonddowning\Honey\Tests;

use Illuminate\Support\Facades\Crypt;
use Lukeraymonddowning\Honey\Providers\HoneyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [HoneyServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('honey.features', []);
        $app['config']->set('app.key', 'base64:F0OdoHVLC2wHKO2YsAA9EVvilD0O2i5B9TnoBYeg7qc=');
    }
}