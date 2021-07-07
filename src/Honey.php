<?php


namespace Lukeraymonddowning\Honey;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;
use Lukeraymonddowning\Honey\Models\Spammer;

class Honey
{
    protected static Collection $checks;
    protected static $failUsing;
    protected static $config;
    protected $isEnabled = false;
    protected $hooks = [
        'beforeFailing' => []
    ];

    public function __construct(Collection $checks, callable $failUsing, $config)
    {
        static::$checks = $checks;
        static::$failUsing = $failUsing;
        static::$config = $config;

        if (Features::spammerIpTrackingIsEnabled()) {
            $this->registerSpammerTracking();
        }

        $this->isEnabled = in_array(config('app.env'), $config['environments']);
    }

    public function isEnabled()
    {
        return $this->isEnabled;
    }

    public function disable()
    {
        $this->isEnabled = false;
    }
    
    public function enable()
    {
        $this->isEnabled = true;
    }

    protected function registerSpammerTracking()
    {
        $this->beforeFailing(fn(Request $request) => Spammer::markAttempt($request->ip()));
    }

    public function check($data)
    {
        if (!$this->isEnabled()) {
            return true;
        }

        return static::$checks->map->passes($data)->filter()->count() === static::$checks->count();
    }

    public static function failUsing(callable $function)
    {
        static::$failUsing = $function;
    }

    public function beforeFailing(callable $hook)
    {
        $this->hooks['beforeFailing'][] = $hook;
    }

    public function fail()
    {
        if (!$this->isEnabled()) {
            return;
        }

        $this->runHooks('beforeFailing');
        return app()->call(static::$failUsing);
    }

    public function runHooks($type)
    {
        collect($this->hooks[$type])->each(fn($hook) => app()->call($hook));
    }

    public function recaptcha()
    {
        return app('honey-recaptcha');
    }

    public function inputs()
    {
        return app(InputNameSelector::class);
    }

}
