<?php


namespace interfaces\Bootstrap;


/**
 * Interface LoadInterface
 * @package interfaces\Bootstrap
 */
interface LoadInterface
{
    /**
     * LoadInterface constructor.
     * @param $path
     */
    public function __construct($path);

    /**
     * @return LoadInterface
     */
    public function load();

}
