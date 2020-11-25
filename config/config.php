<?php

use Lukeraymonddowning\Honey\InputNameSelectors\StaticInputNameSelector;

return [

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
        Lukeraymonddowning\Honey\Checks\PresentButEmptyCheck::class,
        Lukeraymonddowning\Honey\Checks\MinimumTimePassedCheck::class,
    ],

    /**
     * --------------------------------------------------------------------------
     * Minimum time passed
     * --------------------------------------------------------------------------
     *
     * Here you can alter how long, in seconds, must have passed between
     * a form loading and the request coming back in. This only applies
     * if you have `MinimumTimePassedCheck` enabled in `checks`.
     */
    'minimum_time_passed' => 3,

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
                ]
            ],


        ]
    ],

];