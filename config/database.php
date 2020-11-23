<?php
return [
    'default' => [
        //数据库引擎
        'driver'    => env('DATABASE.DB_DRIVER', 'mysql'),
        //数据库地址
        'host'      => env('DATABASE.DB_HOST', 'localhost'),
        //数据库默认端口
        'port'      => (integer)env('DATABASE.DB_PORT', 3306),
        //数据库默认 DB
        'database'  => env('DATABASE.DB_DATABASE', 'esn'),
        //数据库用户名
        'username'  => env('DATABASE.DB_USERNAME', 'root'),
        //数据库密码
        'password'  => env('DATABASE.DB_PASSWORD', ''),
        //数据库编码
        'charset'   => env('DATABASE.DB_CHARSET', 'utf8'),
        //数据库编码
        'collation' => env('DATABASE.DB_COLLATION', 'utf8_unicode_ci'),
        //数据库模型前缀
        'prefix'    => env('DATABASE.DB_PREFIX', ''),

        'reconnect' => (boolean)env('DATABASE.DB_RECONNECT',true),

        'debug'     => (boolean)env('DATABASE.DB_DEBUG',true),

        'pool'      => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float)env('DATABASE.DB_MAX_IDLE_TIME', 60),
        ]
    ],
];
