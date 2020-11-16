<?php


namespace interfaces\Route;


/**
 * Interface ParamInterface
 * @package interfaces\Route
 */
interface ParamInterface
{
    /**
     * ParamInterface constructor.
     * @param array $gets
     */
    public function __construct(array $gets);

    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param $name
     * @param string $default
     * @param string $filters
     * @return mixed
     */
    public function get($name, $default = '', $filters = '');
}
