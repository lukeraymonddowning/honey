<?php


namespace Lukeraymonddowning\Honey\Providers;


use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Lukeraymonddowning\Honey\Commands\InstallCommand;
use Lukeraymonddowning\Honey\Features;
use Lukeraymonddowning\Honey\Honey;
use Lukeraymonddowning\Honey\Http\Middleware\BlockSpammers;
use Lukeraymonddowning\Honey\Http\Middleware\CheckRecaptchaToken;
use Lukeraymonddowning\Honey\Http\Middleware\PreventSpam;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\Captcha\Recaptcha;
use Lukeraymonddowning\Honey\Views\Hcaptcha as HcaptchaComponent;
use Lukeraymonddowning\Honey\Views\Honey as HoneyComponent;
use Lukeraymonddowning\Honey\Views\Recaptcha as RecaptchaComponent;

class HoneyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            'honey',
            fn() => new Honey(static::getChecks(), self::defaultMethodOfFailing(), static::config())
        );
        $this->app->singleton('honey-recaptcha', fn() => app(Recaptcha::class));
        $this->app->singleton(InputNameSelector::class, fn() => app(static::getInputNameSelectorClass()));
    }

    protected static function getChecks()
    {
        return collect(static::config('checks', []))->map(fn($class) => app($class));
    }

    public static function config($key = null, $default = null)
    {
        return config($key ? "honey.$key" : "honey", $default);
    }

    protected static function defaultMethodOfFailing()
    {
        return fn() => Features::rickrollingEnabled() ? static::rickroll() : abort(422, "You shall not pass!");
    }

    protected static function rickroll()
    {
        throw new HttpResponseException(redirect('https://youtu.be/dQw4w9WgXcQ'));
    }

    protected static function getInputNameSelectorClass()
    {
        $driver = static::config("input_name_selectors.default", "static");
        return static::config("input_name_selectors.drivers.$driver.class");
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'honey');
        $this->runConsoleCommands();
        $this->registerMiddleware();
        $this->registerViewComponents();
    }

    protected function runConsoleCommands()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([__DIR__ . '/../../config/config.php' => config_path('honey.php')], 'honey');
        $this->commands(InstallCommand::class);

        if (Features::spammerIpTrackingIsEnabled()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }

    protected function registerMiddleware()
    {
        $router = app(Router::class);
        $router->aliasMiddleware('honey', PreventSpam::class);
        $router->aliasMiddleware('honey-recaptcha', CheckRecaptchaToken::class);
        $router->aliasMiddleware('honey-block', BlockSpammers::class);

        if (Features::blockSpammersGloballyIsEnabled()) {
            app(Kernel::class)->pushMiddleware(BlockSpammers::class);
        }
    }

    protected function registerViewComponents()
    {
        Blade::component(HoneyComponent::class, 'honey');
        Blade::component(RecaptchaComponent::class, 'honey-recaptcha');
        Blade::component(HcaptchaComponent::class, 'honey-hcaptcha');
    }

}