<?php


namespace helper;


use interfaces\ConfigInterface;

/**
 * Class Config
 * @package system\app
 */
class Config implements ConfigInterface
{

    /**
     * @var array|null
     */
    protected static $config = [];

    /**
     * Config constructor.
     * @param string $dir
     * @param string $ext
     */
    private function __construct($dir = '', $ext = '.php')
    {
        self::$config = $this->getDir($dir,$ext);
        return self::class;
    }

    public static function __make($path, $ext)
    {
        return new static($path, $ext);
    }

    /**
     * @param $name
     * @param null $default
     * @return array|mixed|null
     */
    public static function get($name, $default = null)
    {
        return self::getVar($name, $default);
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public static function set($name, $value)
    {
        return self::setVar($name, $value);
    }

    /**
     * @param $name
     * @param null $default
     * @return array|null
     */
    private static function getVar($name, $default = null)
    {
        $variable = self::$config;
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
        if (count($names) == 0) {
            if (!isset($variable)) {
                $variable = $default;
            }
            return $variable;
        }
        return self::getIndex($variable[array_shift($names)], $names, $default);
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    private static function setVar($name, $value)
    {

        if ($name == '*') {
            return self::$config = $value;
        }

        $names = explode('.', $name);
        return self::setIndex(self::$config, $names, $value);
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
     * @param $dir
     * @param string $ext
     * @return array
     */
    private function getDir(string $dir,string $ext = 'php')
    {
        $dirs = scandir($dir);
        $config = [];
        foreach ($dirs as $key => $file) {
            $path = $dir . '/' . $file;
            if ($file != '.' && $file != '..' && preg_match("/.*\.{$ext}$/", $file)) {
                if (is_dir($path)) {
                    $config[$file] = $this->getDir($path);
                } elseif (is_file($path)) {
                    $tmp = include $path;
                    if (is_array($tmp)) {
                        [$index] = explode('.', $file);
                        if (!(isset($config[$index]) && is_array($config[$index]))) {
                            $config[$index] = [];
                        }
                        $config[$index] = array_merge_recursive($config[$index], $tmp);
                    }
                }
            }
        }
        return $config;
    }
}
