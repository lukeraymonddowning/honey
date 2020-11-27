<?php


namespace Lukeraymonddowning\Honey\Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Lukeraymonddowning\Honey\Tests\TestCase as HoneyTestCase;

abstract class TestCase extends HoneyTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate')->run();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('honey.environments', ['testing']);
        $app['config']->set('database.default', 'testing');
    }
}