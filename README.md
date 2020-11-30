#NS Framework

## 序言

1. [开发、使用文档](doc/main.md "文档")

## .env文件配置

```ini
[APP]
#是否是开发模式
DEV = true

#数据库配置
[DATABASE]
DB_DRIVER = mysql
DB_HOST = 127.0.0.1
DB_PORT = 3306
DB_DATABASE = ns
DB_USERNAME = ns
DB_PASSWORD = ns
DB_CHARSET = utf8mb4
DB_COLLATION = utf8mb4_unicode_ci
DB_PREFIX =
DB_RECONNECT = true
DB_DEBUG = true

#HTTP服务器参数配置
[HTTP]
HOST = 0.0.0.0
PORT = 8008

```

## 启动

```shell script
# Phar包启动方式
cd build
php ns.phar app start
```

![](doc/static/start.jpg)

## 停止

```shell script
php ns.phar app stop
```


### 引用组件参考连接
https://github.com/inhere/php-console/wiki


https://github.com/box-project/box/blob/master/doc/configuration.md


https://github.com/nikic/FastRoute
