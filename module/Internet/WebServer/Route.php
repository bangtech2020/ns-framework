<?php


namespace module\Internet\WebServer;


use bootstrap\load;
use FastRoute\Dispatcher;
use helper\Config;
use interfaces\Internet\RouteInterface;

class Route implements RouteInterface
{
    /**
     * @var Dispatcher $dispatcher
     */
    private static $dispatcher;

    public function __construct()
    {
        $this->init();
    }

    public static function __make()
    {
        new static();
    }

    protected function init()
    {
        self::$dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $routeCollector) {

            //系统路由
            $system_routes = Config::get('route');

            //系统路由-路由
            foreach ($system_routes['route'] as $system_route) {
                $routeCollector->addRoute($system_route[0],$system_route[1],$system_route[2]);
            }

            //系统路路由-路由组
            foreach ($system_routes['group'] as $group => $system_route) {
                $routeCollector->addGroup("/{$group}", function (\FastRoute\RouteCollector $r) use ($system_route) {
                    foreach ($system_route as $value) {
                        $r->addRoute($value['mode'], $value['route'], $value['handler']);
                    }
                });
            }

            //扩展应用路由
            $extend_routes = load::getRoutes();
            foreach ($extend_routes as $id => $extend_route) {
                [$author,$identification] = explode('/',$id);
                $routeCollector->addGroup("/{$author}/{$identification}/", function (\FastRoute\RouteCollector $r) use ($extend_route,$id) {
                    foreach ($extend_route as $value) {
                        $r->addRoute($value['mode'], $value['route'], "app\\{$id}\\{$value['handler']}");
                    }
                });
            }


        });
    }

    public static function dispatch($httpMethod, $uri)
    {
        return self::$dispatcher->dispatch($httpMethod, $uri);
    }
}
