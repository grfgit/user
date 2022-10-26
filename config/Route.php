<?php

namespace Config;

/**
 * Class Route
 */
class Route {

    public static $urls = [
        /*
         * User Controller
         */
        'user' => [
            'index' => [
                'url' => 'user',
                'method' => 'get'
            ],
            'create' => [
                'url' => 'user',
                'method' => 'post'
            ]
        ]
    ];

}
