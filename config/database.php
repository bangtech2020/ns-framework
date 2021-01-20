<?php

use helper\Env;

return [
    'default' => [
        //数据库引擎
        'driver'    => Env::get('DATABASE.DB_DRIVER', 'mysql'),
        //数据库地址
        'host'      => Env::get('DATABASE.DB_HOST', 'localhost'),
        //数据库默认端口
        'port'      => (integer)Env::get('DATABASE.DB_PORT', 3306),
        //数据库默认 DB
        'database'  => Env::get('DATABASE.DB_DATABASE', 'esn'),
        //数据库用户名
        'username'  => Env::get('DATABASE.DB_USERNAME', 'root'),
        //数据库密码
        'password'  => Env::get('DATABASE.DB_PASSWORD', ''),
        //数据库编码
        'charset'   => Env::get('DATABASE.DB_CHARSET', 'utf8'),
        //数据库编码
        'collation' => Env::get('DATABASE.DB_COLLATION', 'utf8_unicode_ci'),
        //数据库模型前缀
        'prefix'    => Env::get('DATABASE.DB_PREFIX', ''),

        'reconnect' => (boolean)Env::get('DATABASE.DB_RECONNECT',true),

        'debug'     => (boolean)Env::get('DATABASE.DB_DEBUG',true),

        'pool'      => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float)Env::get('DATABASE.DB_MAX_IDLE_TIME', 60),
        ]
    ],
];
