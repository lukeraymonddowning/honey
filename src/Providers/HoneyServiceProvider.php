<?php


namespace Lukeraymonddowning\Honey\Providers;


use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Lukeraymonddowning\Honey\Features;
use Lukeraymonddowning\Honey\Honey;
use Lukeraymonddowning\Honey\Http\Middleware\BlockSpammers;
use Lukeraymonddowning\Honey\Http\Middleware\PreventSpam;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\InputNameSelectors\StaticInputNameSelector;
use Lukeraymonddowning\Honey\Views\Honey as HoneyComponent;

class HoneyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('honey', fn() => new Honey(static::getChecks(), self::defaultMethodOfFailing()));
        $this->app->singleton(InputNameSelector::class, fn() => app(static::getInputNameSelectorClass()));
        $this->app->singleton(
            StaticInputNameSelector::class,
            fn() => new StaticInputNameSelector(config('honey.input_name_selectors.drivers.static.names'))
        );
    }

    protected static function getChecks()
    {
        return collect(config('honey.checks', []))->map(fn($class) => app($class));
    }

    protected static function defaultMethodOfFailing()
    {
        return fn() => abort(422, "You shall not pass!");
    }

    protected static function getInputNameSelectorClass()
    {
        $driver = config("honey.input_name_selectors.default");
        return config("honey.input_name_selectors.drivers.$driver.class");
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'honey');

        if ($this->app->runningInConsole()) {
            $this->console();
        }

        $this->registerMiddleware();

        Blade::component(HoneyComponent::class, 'honey');
    }

    public function console()
    {
        if (Features::spammerIpTrackingIsEnabled()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }

        $this->publishes(
            [
                __DIR__ . '/../../config/config.php' => config_path('honey.php'),
            ],
            'honey'
        );
    }

    protected function registerMiddleware()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('honey', PreventSpam::class);
        $router->aliasMiddleware('honey-block', BlockSpammers::class);

        if (Features::blockSpammersGloballyIsEnabled()) {
            $kernel = $this->app->make(Kernel::class);
            $kernel->pushMiddleware('honey-block');
        }
    }

}