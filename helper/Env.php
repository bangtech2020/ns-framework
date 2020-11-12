<?php

declare(strict_types=1);

namespace helper;

use interfaces\ConfigInterface;

/**
 * Class Env
 * @package system\app
 */
class Env implements ConfigInterface
{
    /**
     * @var array
     */
    protected static $env = [];

    /**
     * Env constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        self::$env = $this->arraykeyToLower($this->loadEnv($path));
        return self::class;
    }

    public static function __make($path)
    {
        return new static($path);
    }

    /**
     * @param $name
     * @param null $default
     * @return array|mixed|null
     */
    public static function get($name, $default = null)
    {
        $name = strtolower($name);
        return self::getVar($name, $default);
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public static function set($name, $value)
    {
        $name = strtolower($name);
        return self::setVar($name, $value);
    }

    /**
     * @param $name
     * @param null $default
     * @return array|null
     */
    private static function getVar($name, $default = null)
    {
        $variable = self::$env;
        if ($name == '*') {
            return $variable;
        }

        $names = explode('.', $name);
        return self::getIndex($variable, $names, $default);
    }

    /**
     * @param $variable
     * @param $names
     * @param null $default
     * @return |null
     */
    private static function getIndex($variable, $names, $default = null)
    {
        //如果键已经被全部加载进去后直接返回值
        if (count($names) == 0) {
            if (!isset($variable)) {
                $variable = $default;
            }
            return $variable;
        }
        $index = array_shift($names);
        //如果数组里面不存在对应的键则直接返货默认值
        if(!isset($variable[$index])){
            return $default;
        }
        return self::getIndex($variable[$index], $names, $default);
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    private static function setVar($name, $value)
    {

        if ($name == '*') {
            return self::$env = $value;
        }

        $names = explode('.', $name);
        return self::setIndex(self::$env, $names, $value);
    }

    /**
     * @param $variable
     * @param $names
     * @param $value
     * @return mixed
     */
    private static function setIndex(&$variable, &$names, &$value)
    {
        if (count($names) == 0) {
            return $variable = $value;
        }
        self::setIndex($variable[array_shift($names)], $names, $value);
    }

    /**
     * @return array|false|null
     */
    public function loadEnv($file)
    {
        if (is_file($file)){
            return $this->env = parse_ini_file($file, true);
        }else{
            return $this->env = [];
        }
    }

    /**
     * 多维数组键转小写
     * @param $arr
     * @return array
     */
    protected function arraykeyToLower($arr)
    {
        $arr = array_change_key_case($arr,CASE_LOWER);
        foreach ($arr as $key => &$value) {
            if (is_array($value)){
                $value = $this->arraykeyToLower($value);
            }
        }
        return $arr;
    }
}
