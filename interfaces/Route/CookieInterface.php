<?php


namespace interfaces\Route;


/**
 * Interface CookieInterface
 * @package interfaces\Route
 */
interface CookieInterface extends ParamInterface
{
    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value);
}
