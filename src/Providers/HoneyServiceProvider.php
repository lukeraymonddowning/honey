<?php


namespace Lukeraymonddowning\Honey\Providers;


use Illuminate\Support\ServiceProvider;
use Lukeraymonddowning\Honey\Honey;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\InputNameSelectors\StaticInputNameSelector;

class HoneyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('honey', fn() => new Honey(static::getChecks()));
        $this->registerInputNameClasses();
    }

    protected static function getChecks()
    {
        return collect(config('honey.checks', []))->map(fn($class) => app($class));
    }

    protected function registerInputNameClasses()
    {
        $this->app->singleton(InputNameSelector::class, fn() => app($this->getInputNameSelectorClass()));
        $this->app->singleton(
            StaticInputNameSelector::class,
            fn() => new StaticInputNameSelector(config('honey.input_name_selectors.drivers.static.names'))
        );
    }

    protected function getInputNameSelectorClass()
    {
        $driver = config("honey.input_name_selectors.default");
        return config("honey.input_name_selectors.drivers.$driver.class");
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->console();
        }

        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'honey');
    }

    protected function console()
    {
        $this->publishes(static::filesToPublish(), 'honey');
    }

    protected static function filesToPublish()
    {
        return [
            __DIR__ . '/../../config/config.php' => config_path('honey.php'),
        ];
    }

}