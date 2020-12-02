<?php

use Lukeraymonddowning\Honey\Checks\JavascriptInputFilledCheck;
use Lukeraymonddowning\Honey\Checks\MinimumTimePassedCheck;
use Lukeraymonddowning\Honey\Checks\PresentButEmptyCheck;
use Lukeraymonddowning\Honey\Checks\UserIsBlockedSpammerCheck;
use Lukeraymonddowning\Honey\Features;
use Lukeraymonddowning\Honey\InputNameSelectors\StaticInputNameSelector;

return [

    /**
     * --------------------------------------------------------------------------
     * Features
     * --------------------------------------------------------------------------
     *
     * Here you can enable or disable different features that Honey provides.
     * You should read the documentation for a more detailed look at each
     * feature offered and the steps required to get it set up.
     */
    'features' => [
        Features::spammerIpTracking(),
        Features::blockSpammersGlobally(),
//        Features::neverGonnaGiveYouUp(),
    ],

    /**
     * --------------------------------------------------------------------------
     * Environments
     * --------------------------------------------------------------------------
     *
     * You probably don't want to run Honey all the time, especially in tests and such.
     * Here you can select the specific environments that Honey should run in. We've
     * enabled `local` for you to test, but you might want to comment it out for
     * day to day use.
     */
    'environments' => [
        'production',
        'local'
    ],

    /**
     * --------------------------------------------------------------------------
     * Checks
     * --------------------------------------------------------------------------
     *
     * These checks will be performed as part of the honey spam test. If one of
     * them returns false, the request is regarded as being spam and should
     * be ignored. You can disabled anything you don't need.
     *
     * @see \Lukeraymonddowning\Honey\Checks\Check
     */
    'checks' => [
        UserIsBlockedSpammerCheck::class,
        PresentButEmptyCheck::class,
        MinimumTimePassedCheck::class,
        JavascriptInputFilledCheck::class,
    ],

    /**
     * --------------------------------------------------------------------------
     * Minimum time passed
     * --------------------------------------------------------------------------
     *
     * Here you can alter how long, in seconds, must have passed between
     * a form loading and the request coming back in if you have either
     * `MinimumTimePassedCheck` or `JavascriptInputFilledCheck` enabled.
     */
    'minimum_time_passed' => 3,

    /**
     * --------------------------------------------------------------------------
     * Spammer blocking
     * --------------------------------------------------------------------------
     *
     * If you have the 'spammerIpTracking' feature enabled, Honey
     * will automatically track and block repeat spammers from
     * your application. You must have a db connection.
     */
    'spammer_blocking' => [
        /**
         * The db table name that should be used to track spammers
         */
        'table_name' => 'spammers',
        /**
         * The number of times a request from an ip address can be classed
         * as spam before they are added to the block list.
         */
        'maximum_attempts' => 5
    ],

    /**
     * --------------------------------------------------------------------------
     * Input name selectors
     * --------------------------------------------------------------------------
     *
     * The input names, ids and, when using the Livewire stack,
     * model bindings, are decided on by the driver selected
     * here.
     */
    'input_name_selectors' => [
        'default' => 'static',
        'drivers' => [

            /**
             * The static driver uses the defined input names.
             * As the name implies, the values don't change.
             */
            'static' => [
                'class' => StaticInputNameSelector::class,
                'names' => [
                    'present_but_empty' => 'honey_present',
                    'time_of_page_load' => 'honey_time',
                    'javascript_input' => 'honey_javascript',
                    'recaptcha_input' => 'honey_recaptcha_token'
                ]
            ],

        ]
    ],

    /**
     * --------------------------------------------------------------------------
     * Input values
     * --------------------------------------------------------------------------
     *
     * The honeypot inputs in Honey can be configured to provide
     * different values. Not all inputs support this feature.
     */
    'input_values' => [
        'javascript' => Lukeraymonddowning\Honey\InputValues\JavascriptInputValue::class,
        'time_of_page_load' => Lukeraymonddowning\Honey\InputValues\TimeOfPageLoadValue::class,
    ],

    /**
     * --------------------------------------------------------------------------
     * Recaptcha
     * --------------------------------------------------------------------------
     *
     * If you decide to use Honey's Google Recaptcha integration, you
     * can configure it by editing the values here or in your .env
     * file.
     */
    'recaptcha' => [
        'site_key' => env("RECAPTCHA_SITE_KEY"),
        'secret_key' => env("RECAPTCHA_SECRET_KEY"),
        /**
         * Recaptcha returns a score between 0 and 1 when checking a
         * token. 0 is most definitely a bot, 1 is definitely a
         * user. This informs the recaptcha middleware of
         * minimum score a user can get to pass.
         */
        'minimum_score' => env("RECAPTCHA_MINIMUM_SCORE", 0.5),
        /**
         * The Recaptcha input will request a token on page load. As
         * Recaptcha tokens only last for 2 minutes, the input
         * refreshes based on this timeout (in milliseconds).
         */
        'token_refresh_interval' => 60000,
    ]

];