<?php


namespace helper;


use DI\ContainerBuilder;

class Di
{
    private static $di;
    private static $container;

    private function __construct()
    {
        self::$di = new ContainerBuilder();
        /*$this->di->addDefinitions([
            ConfigInterface::class => create(Config::class)
        ]);*/
        self::$container = self::$di->build();
    }

    public static function __make(){
        return new static();
    }

    /**
     * @return ContainerBuilder
     */
    public static function getDi(): ContainerBuilder
    {
        return self::$di;
    }

    /**
     * @return \DI\Container
     */
    public static function getContainer(): \DI\Container
    {
        return self::$container;
    }
}
