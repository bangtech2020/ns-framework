<?php


namespace helper;

use bangtech\swooleOrm\db\Query;
use bangtech\swooleOrm\MysqlPool;

class Db
{

    /**
     * @var MysqlPool
     */
    private static $mysqlPool;

    private static $config = [];


    /**
     * Db constructor.
     * @return Query
     */
    public function __construct()
    {
        self::$config = [
            'host'      => Config::get('database.default.host','127.0.0.1'), //服务器地址
            'port'      => Config::get('database.default.port',3306),        //端口
            'user'      => Config::get('database.default.username','root'),  //用户名
            'password'  => Config::get('database.default.password',''),      //密码
            'charset'   => Config::get('database.default.charset','utf8'),   //编码
            'database'  => Config::get('database.default.database','esn'),   //数据库名
            'prefix'    => Config::get('database.default.prefix',''),        //表前缀
            'poolMin'   => 5,        //空闲时，保存的最大链接，默认为5
            'poolMax'   => 1000,     //地址池最大连接数，默认1000
            'clearTime' => 60000,   //清除空闲链接定时器，默认60秒，单位ms
            'clearAll'  => 300000,  //空闲多久清空所有连接，默认5分钟，单位ms
            'setDefer'  => true,    //设置是否返回结果,默认为true,
        ];
        self::$mysqlPool = new MysqlPool(self::$config);
        return \bangtech\swooleOrm\Db::init(self::$mysqlPool);
    }

    public static function __make(){
        return new static();
    }

    /**
     * @param string $tableName
     * @return Query
     */
    public static function name($tableName = '')
    {
        return (new Query())->init(self::$mysqlPool)->name($tableName);
    }

    public static function table($tableName = '')
    {
        return (new Query())->init(self::$mysqlPool)->table($tableName);
    }
}
