<?php
declare(strict_types=1);

namespace interfaces;


/**
 * Interface ConfigInterface
 * @package system\Interfaces
 */
interface ConfigInterface
{
    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public static function get($name, $default = null);

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public static function set($name, $value);
}
