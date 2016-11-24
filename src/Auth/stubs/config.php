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

            'userProvider' => [
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

            'userProvider' => [
                'type' => 'phalcon.pdo',
                'options' => [
                    'table' => 'users',
                    'identifierColumn' => 'id',
                    'passwordColumn' => 'password'
                ]
            ]

        ]

    ]
);