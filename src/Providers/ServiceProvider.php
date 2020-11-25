<?php


namespace Lukeraymonddowning\Honey\Providers;


use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

abstract class ServiceProvider extends LaravelServiceProvider
{

    protected static function getChecks()
    {
        return collect(config('honey.checks', []))->map(fn($class) => app($class));
    }

    protected static function getInputNameSelectorClass()
    {
        $driver = config("honey.input_name_selectors.default");
        return config("honey.input_name_selectors.drivers.$driver.class");
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            static::publish();
        }
    }

    abstract static function publish();

}