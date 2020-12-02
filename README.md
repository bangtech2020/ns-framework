# NS Framework

## 序言
您看到的本项目现在是开发框架并非线上使用框架，上线请打包
1. [开发、使用文档](doc/main.md "文档")

## 初衷
目标：
1. 运维部署麻烦。
2. 中小公司项目尝试多变，但是很多已开发完成的模块是可以复用的，但是复用需要手动拷贝很多代码和依赖
3. 降本增效

## 待发布更新功能
1. 应用代码composer隔离
2. 应用安装依赖
3. 数据库模型
4. 数据库模型生成

## 框架说明(开发版)
> 注意：框架开发后需要打包成Pahr运行文件

> 上线后请运行Phar包

### 1. 框架结构
```markdown
www  WEB部署目录（或者子目录）
├─app                                应用目录
│  └─ ...                            
│
├─bin                                应用入口
│  └─ns.php                          应用启动入口文件
│
├─bootstrap                          项目启动引导
│  ├─app.php                         框架引导实例化入口
│  └─load.php                        框架相关配置文件加载入口
│  
├─brand                              品牌Logo文件夹
│  └─ns_logo.text                    框架品牌Logo文本格式
│  
├─build                              框架打包生成目录
│  └─ ...                       
│ 
├─commands                           框架自带命令行
│  ├─App
│  │  ├─template
│  │  │  ├─command
│  │  │  │  └─DemoCommand.php.tph
│  │  │  └─extend
│  │  │     └─Index.php.tph
│  │  │
│  │  ├─CreateCommand.php
│  │  ├─InstallCommand.php
│  │  ├─LoadCommand.php
│  │  ├─PackCommand.php
│  │  ├─UninstallCommand.php
│  │  └─WebServiceCommand.php  
│  │
│  ├─Server
│  │  └─InternetCommand.php
│  │
│  ├─AppCommand.php 
│  └─ServerCommand.php 
│
├─config                              配置目录
│  ├─app.php                          框架应用配置
│  ├─commands.php                     框架控制台配置
│  ├─database.php                     全局数据库配置(包含框架和应用)
│  ├─event.php                        框架事件配置
│  └─route.php                        框架路由配置
│
├─doc                                 文档
│
├─eventListener                       框架监听事件
│  ├─AppStartEventListener.php        框架启动监听事件
│  └─AppStopEventListener.php         框架停止监听事件
│
├─extend                              非Composer扩展
│  └─Table                            基于PHP数组的简易型内存数据库
│
├─functions                           框架定义的函数
│  └─common.php                       框架定义的函数
│
├─helper                              助手工具集合
│  ├─Console                          控制台
│  │  ├─CommandGroup.php              控制台命令组抽象类
│  │  └─Command.php                   控制台命令抽象类
│  │
│  ├─Event                            事件
│  │  ├─Event.php                     事件注册
│  │  └─Listener.php                  事件触发
│  │
│  ├─Internet                         Web网络
│  │  ├─Request                       请求对象组
│  │  │  ├─Cookie.php                 Cookie助手
│  │  │  ├─File.php                   文件助手
│  │  │  ├─Get.php                    Get参数助手
│  │  │  ├─Header.php                 Header参数助手
│  │  │  ├─Param.php                  URL参数助手
│  │  │  ├─Post.php                   POST参数助手
│  │  │  └─Server.php                 Server参数助手
│  │  │
│  │  ├─Controller.php                控制器抽象类
│  │  ├─Request.php                   请求抽象类
│  │  └─Response.php                  响应抽象类
│  │
│  ├─WebServer                        Web服务
│  │  ├─Request.php                   请求对象助手
│  │  ├─Response.php                  响应对象助手
│  │  ├─Route.php                     路由对象
│  │  ├─Server.php                    Web服务对象
│  │  └─WebService.php                Web服务绑定对象
│  │ 
│  ├─Config.php                       配置助手
│  ├─Console.php                      控制台助手
│  ├─Db.php                           数据库助手
│  ├─Di.php                           DI助手
│  └─Env.php                          Env助手
│
├─interfaces                          接口
│  ├─Bootstrap                        框架启引导
│  │  └─LoadInterface.php             框架启引导接口
│  │
│  ├─Console                          控制台相关接口
│  │  ├─InputInterface.php            框架启引导接口
│  │  └─OutputInterface.php           框架启引导接口
│  │
│  ├─Internet                         网络服务部分接口
│  │  ├─Request                       请求对象接口
│  │  │  ├─CookieInterface.php        Cookie接口
│  │  │  ├─FileInterface.php          文件接口
│  │  │  ├─GetInterface.php           Get参数接口
│  │  │  ├─HeaderInterface.php        Header参数接口
│  │  │  ├─ParamInterface.php         URL参数接口
│  │  │  ├─PostInterface.php          POST参数接口
│  │  │  └─ServerInterface.php        Server参数接口
│  │  │
│  │  ├─RequestInterface.php          请求对象接口
│  │  ├─ResponseInterface.php         响应对象接口
│  │  └─RouteInterface.php            路由对象接口
│  │
│  └─ConfigInterface.php              配置助手接口
│
├─module                              模块
│  ├─app                              框架管理部分代码
│  │  └─ ...                          （包含模型生成等,未完成）
│  └─Internet                         网络部分
│     └─Service.php                   Web服务启动类
│
├─public                              WEB目录（框架启动后自动生成）
│  └─ ...                             框架启动后自动生成
│
├─runtime                             应用的运行时目录
├─vendor                              Composer类库目录
├─.env.default                        配置变量示例文件
├─box.json                            框架打包配置文件
├─composer.json                       composer 定义文件
├─LICENSE.txt                         授权说明文件
└─README.md                           README 文件
```
### 2. 框架配置
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
### 3. 框架启动

#### 启动

```shell script
php bin/ns.php app start
```

#### 停止

```shell script
php bin/ns.php app stop
```

### 4. 框架打包

```shell script
vendor/bin/box compile
```

## 应用开发说明
> 注意：应用打包成功后需要放在对应的目录

> 正在计划支持自动包依赖关系

### 1. 应用创建
```shell script
# 开发框架
php bin/ns.php app:create

# 正式版Phar包
php ns.phar app:create
```

![](doc/static/F40E8F6C-68B0-4d77-BC37-AE7DD87A85C1.png)

### 2. 应用结构

#### 目录结构

```markdown
app                                   app目录
├─bangtech                            作者
│  ├─test                             应用
│  │  ├─command                       控制台命令
│  │  │  └─DemoCommand.php            Demo命令
│  │  ├─extend                        扩展HTTP API
│  │  │  └─Index.php                  Demo扩展
│  │  └─app.json                      app.json
│  │ 
│  └─ ...                             其他更多应用
└─ ...                                其他更多作者
```

#### app.json说明

```json
{
  "name": "bangtech/test",
  "author": "bangtech",
  "identification": "test",
  "version": "0.0.1",
  "description": "",
  "copyright": "",
  "event": {
    "app_start": ["event\\EventListener@app_start"],
    "app_stop" : ["event\\EventListener@app_stop"]
  },
  "extend": [
    {
      "mode": "GET",
      "route": "index/abc",
      "handler": "extend\\Index@index"
    }
  ],
  "command": [
    {"class": "command\\WebServerCommand", "mode": "HAS_COMMAND"}
  ]
}
```

- name 完整包名
- author 作者
- identification 标识
- version 版本(x.x.x)
- description 描述
- copyright 版权
- event 监听事件
    - 事件名
         - array[] 事件类位置和方法，也可以直接一个类文件注册成一个事件
- extend 扩展页面
    - array[]
        - mode 模式[GET|POST|OPTIONS|HEAD|PUT|DELETE|TRACE|CONNECT]
        - route 路由地址(例：index/abc 实际地址是 /bangtech/test/index/abc)
        - handler 类位置和方法(例：extend\\Index@index)
- command 扩展控制台
    - array
        - class 类名位置(相对应用)
        - mode 模式[HAS_COMMAND|HAS_GROUP]

### 3. 应用打包
```shell script
php bin/ns.php app:pack bangtech/test
```

## 系统部署说明
### 安装
### 配置
### 启动
### 应用安装

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
