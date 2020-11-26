<?php

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
        Lukeraymonddowning\Honey\Checks\UserIsBlockedSpammerCheck::class,
        Lukeraymonddowning\Honey\Checks\PresentButEmptyCheck::class,
        Lukeraymonddowning\Honey\Checks\MinimumTimePassedCheck::class,
//        Lukeraymonddowning\Honey\Checks\AlpineInputFilledCheck::class,
    ],

    /**
     * --------------------------------------------------------------------------
     * Minimum time passed
     * --------------------------------------------------------------------------
     *
     * Here you can alter how long, in seconds, must have passed between
     * a form loading and the request coming back in if you have either
     * `MinimumTimePassedCheck` or `AlpineInputFilledCheck` enabled.
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
                    'alpine_input' => 'honey_alpine',
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
        'alpine' => Lukeraymonddowning\Honey\InputValues\AlpineInputValue::class,
    ],

];