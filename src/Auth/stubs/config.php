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
    'defaults' => [
        'driver' => 'session',
        'cookieServiceName' => 'cookies', // optional
        'sessionServiceName' => 'session', // optional
        'hashingServiceName' => 'security', // optional
        'userManager' => [
            'type' => 'phalcon.model',
            'options' => [
                'model' => '\App\Models\Users'
            ]
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Available drivers' config
    |--------------------------------------------------------------------------
    | 
    */
    'drivers' => [

        /*
        |--------------------------------------------------------------------------
        | Session driver settings
        |--------------------------------------------------------------------------
        */
        'session' => [

            'sessionServiceName' => 'session',

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
                    'tokenColumn' => 'auth_token'
                ]
            ]

        ]

    ]
);