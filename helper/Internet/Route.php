<?php


namespace helper\Internet;


use interfaces\RouteInterface;

class Route implements RouteInterface
{
    private static $rules = [];
    private static $dispatcher;

    public function __construct()
    {
        $this->init();
    }

    public function __make()
    {
        new static();
    }

    protected function init()
    {
        self::$dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $routeCollector) {
            $routeCollector->addGroup('/admin', function (\FastRoute\RouteCollector $r) {
                $r->addRoute('GET', '/do-something', 'handler');
                $r->addRoute('GET', '/do-another-thing', 'handler');
                $r->addRoute('GET', '/do-something-else', 'handler');
            });
        });
    }
}
