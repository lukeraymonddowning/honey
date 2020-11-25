<?php

namespace Lukeraymonddowning\Honey\Tests;

use Lukeraymonddowning\Honey\Providers\HoneyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{

    protected function getPackageProviders($app)
    {
        return [HoneyServiceProvider::class];
    }

}