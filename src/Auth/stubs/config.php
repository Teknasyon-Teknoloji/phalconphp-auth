<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 21/11/2016
 * Time: 13:17
 */


return array(

    /*
    |--------------------------------------------------------------------------
    | Default settings
    |--------------------------------------------------------------------------
    |
    */
    'driver' => 'session',
    'cookieServiceName' => 'cookies', // optional
    'sessionServiceName' => 'session', // optional
    'hashingServiceName' => 'security', // optional
    'userManager' => [
        'type' => 'phalcon.model',
        'options' => [
            'model' => '\App\Models\Users'
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Available drivers' config
    |--------------------------------------------------------------------------
    | Configurations can be set per driver.
    */
    'drivers' => [

        /*
        |--------------------------------------------------------------------------
        | Session driver settings
        |--------------------------------------------------------------------------
        */
        'session' => [

            'sessionServiceName' => 'session', // overrides the default setting. can be removed.

            'hashingServiceName' => 'security',

            'userManager' => [
                'type' => 'phalcon.model',
                'options' => [
                    'model' => '\App\Models\Users'
                ]
            ]

        ],

        /*
        |--------------------------------------------------------------------------
        | Token driver settings
        |--------------------------------------------------------------------------
        | /!\ WARNING : This driver is not ready to use.
        |
        */
        'token' => [

            'requestServiceName' => 'request',

            'hashingServiceName' => 'security',

            'userManager' => [
                'type' => 'phalcon.pdo',
                'options' => [
                    'table' => 'users',
                    'identifierColumn' => 'id',
                    'passwordColumn' => 'password',
                    'authTokenColumn' => 'auth_token'
                ]
            ]

        ]

    ]
);