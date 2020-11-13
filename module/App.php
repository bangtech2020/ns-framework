<?php


namespace module;


use helper\Config;

class App
{
    private static $path = '';
    private static $apps = [];

    public static function __make($path)
    {
        return new static($path);
    }

    private function __construct($path)
    {
        self::$path = $path;
        $this->init();
        return self::class;
    }

    public function init() :void
    {
        if (!is_dir(self::$path)) {
            throw new \Exception('This not directory!');
        }

        $map = scandir(self::$path);

        $apps = [];

        $has_dev = Config::get('app.has_dev', false);

        //文件匹配正则表达式
        $pattern = "/^(.*)\.phar$/";

        //加载包
        foreach ($map as $file) {
            if ($file != '.' && $file != '..') {

                try {
                    if (is_file(self::$path . '/' . $file)) {
                        if (preg_match($pattern, strtolower($file), $matches)) {
                            $appSetting = $this->getAppSetting(self::$path . '/' . $file,'app.json');
                            $apps[strtolower($file)] = ['phar', $file ,self::$path . '/' . $file,$appSetting];
                        }
                    }

                    //只有在开发模式下才进行加载文件夹
                    if (HAS_DEV && (HAS_DEV || $has_dev) && is_dir(self::$path . '/' . $file)) {
                        $appSetting = $this->getAppSetting(self::$path . '/' . $file,'app.json');
                        $apps[strtolower($file)] = ['dir', $file ,self::$path . '/' . $file,$appSetting];
                    }
                }
                //异常处理
                catch (\ErrorException $exception){}
                catch (\Error $exception){}
                catch (\Exception $exception){}
            }
        }

        self::$apps = $apps;
    }

    private function getAppSetting($dirPath, $fileName)
    {
        $content = file_get_contents("{$dirPath}/{$fileName}");
        return json_decode($content,true);
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
        if (array_key_exists($name,self::$apps)) return self::$apps[$name];
        return false;
    }

}
