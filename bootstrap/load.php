<?php


namespace bootstrap;


use helper\Config;
use helper\Di;
use interfaces\Console\OutputInterface;

class load
{
    private static $path = '';
    private static $apps = [];
    private static $commands = [];
    private static $routes = [];
    private static $events = [];

    /**
     * @return string
     */
    public static function getPath(): string
    {
        return self::$path;
    }


    /**
     * @return array
     */
    public static function getCommands(): array
    {
        return self::$commands;
    }

    /**
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * @return array
     */
    public static function getEvents(): array
    {
        return self::$events;
    }


    public static function __make($path)
    {
        return new static($path);
    }

    public static function autoload($class_name)
    {
        $has_dev = Config::get('app.dev', false);
        $file = preg_replace('/(\\\\+)+/', '/', $class_name);


        //重写Phar路径
        $file_phar = explode('/', $file);
        if ($file_phar[0] != 'app') {
            return;
        }

        $file_phar[2] = $file_phar[2] . '.phar';

        $phar_path = ROOT_PATH . "/{$file_phar[0]}/{$file_phar[1]}/{$file_phar[2]}";
        $file_phar = implode('/', $file_phar);


        $file = ROOT_PATH . '/' . $file . '.php';
        $file_phar = 'Phar://' . ROOT_PATH . '/' . $file_phar . '.php';

        //开发模式下直接通过文件夹加载，不读取phar
        if ($has_dev && is_file($file)) {
            require $file;
            return;
        }

        if (!is_file($phar_path)) {
            try {
                Di::getContainer()->get(OutputInterface::class)->warning("File not found for [{$phar_path}]");
            } catch (\Throwable $exception) {
                var_dump("File not found for [{$phar_path}]");
            }
        }

        if (is_file($phar_path) && is_file($file_phar)) require $file_phar;
    }

    private function __construct($path)
    {
        //注册未定义的APP
        spl_autoload_register([load::class, 'autoload']);
        self::$path = $path;
        //清空文件夹
        delectFileAll(ROOT_PATH.'/public');
        self::init();
        return self::class;
    }

    public static function reload()
    {
        self::init();
    }

    private static function init(): void
    {
        if (!is_dir(self::$path)) {
            self::$apps = [];
            return;
        }

        $apps = [];

        if (!is_file(self::$path . '/ns.lock')) {
            self::$apps = $apps;
            return;
        }

        $nsConfig = file_get_contents(self::$path . '/ns.lock');
        $nsConfig = json_decode($nsConfig, true);
        $packages = $nsConfig['packages'];


        foreach ($packages as $id => $package) {

            //如果没有这个文件则自动创建
            if (!is_dir(ROOT_PATH . "/public/{$id}")) {
                mkdir(ROOT_PATH . "/public/{$id}", 755, true);
            }
            //开始复制静态文件
            if (is_dir(self::$path . "/{$id}")) {
                copyFileAll(self::$path . "/{$id}/resource", ROOT_PATH . "/public/{$id}");
            }

            self::$commands[$id] = $package['setting']['command'];
            self::$events[$id] = $package['setting']['event'];
            self::$routes[$id] = $package['setting']['extend'];
        }

        self::$apps = $apps;
    }

    /**
     * 读取指定的文件配置
     * @param $dirPath
     * @param $fileName
     * @return bool|mixed
     */
    private function getAppSetting($dirPath, $fileName)
    {
        if (!is_file("{$dirPath}/{$fileName}")) {
            return false;
        }
        $content = file_get_contents("{$dirPath}/{$fileName}");
        return json_decode($content, true);
    }

    /**
     * 获取全部App应用列表
     * @return array
     */
    public static function getApps()
    {
        return self::$apps;
    }

    /**
     * 获取指定App
     * @param $name
     * @return bool|mixed
     */
    public static function getApp($name)
    {
        if (array_key_exists($name, self::$apps)) return self::$apps[$name];
        return false;
    }


}
