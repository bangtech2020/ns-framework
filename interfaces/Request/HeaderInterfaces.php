<?php


namespace interfaces\Request;


/**
 * Interface HeaderInterfaces
 * @package interfaces\Route
 */
interface HeaderInterfaces extends ParamInterface
{
    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value);
}
