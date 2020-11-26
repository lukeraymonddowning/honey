<?php


namespace Lukeraymonddowning\Honey;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lukeraymonddowning\Honey\Models\Spammer;

class Honey
{
    protected static Collection $checks;
    protected static $failUsing;
    protected $hooks = [
        'beforeFailing' => []
    ];

    public function __construct(Collection $checks, callable $failUsing)
    {
        static::$checks = $checks;
        static::$failUsing = $failUsing;

        if (Features::spammerIpTrackingIsEnabled()) {
            $this->registerSpammerTracking();
        }
    }

    protected function registerSpammerTracking()
    {
        $this->beforeFailing(fn(Request $request) => Spammer::markAttempt($request->ip()));
    }

    public function check($data)
    {
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
        $this->runHooks('beforeFailing');
        return app()->call(static::$failUsing);
    }

    public function runHooks($type)
    {
        collect($this->hooks[$type])->each(fn($hook) => app()->call($hook));
    }

}