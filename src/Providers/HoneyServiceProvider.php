<?php


namespace Lukeraymonddowning\Honey\Providers;


use Lukeraymonddowning\Honey\Honey;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\InputNameSelectors\StaticInputNameSelector;

class HoneyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('honey', fn() => new Honey(static::getChecks()));
        $this->app->singleton(InputNameSelector::class, fn() => app(static::getInputNameSelectorClass()));
        $this->app->singleton(
            StaticInputNameSelector::class,
            fn() => new StaticInputNameSelector(config('honey.input_name_selectors.drivers.static.names'))
        );
    }

    public function boot()
    {
        parent::boot();
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'honey');
    }

    public static function publish()
    {
        return [
            __DIR__ . '/../../config/config.php' => config_path('honey.php'),
        ];
    }

}