<?php


namespace module\Internet;


use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server;

class WebServer extends \helper\Internet\WebServer
{
    /**
     * @inheritDoc
     */
    public function onStart(Server $server, ...$args)
    {
        // TODO: Implement onStart() method.
    }

    /**
     * @inheritDoc
     */
    public function onShutdown(Server $server, ...$args)
    {
        // TODO: Implement onShutdown() method.
    }

    /**
     * @inheritDoc
     */
    public function onConnect(Server $server, int $fd, int $reactorId, ...$args)
    {
        // TODO: Implement onConnect() method.
    }

    /**
     * @inheritDoc
     */
    public function onClose(Server $server, int $fd, int $reactorId, ...$args)
    {
        // TODO: Implement onClose() method.
    }

    /**
     * @inheritDoc
     */
    public function onRequest(Request $request, Response $response, ...$args)
    {
        // TODO: Implement onRequest() method.
        $response->setStatusCode(404);
        $response->end('404 - Not Found');
        $response->close();
    }

    /**
     * @inheritDoc
     */
    public function onTask(Server $server, int $task_id, int $src_worker_id, $data)
    {
        // TODO: Implement onTask() method.
    }


    public function initRoute()
    {
        $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $routeCollector) {
            $routeCollector->addGroup('/admin', function (\FastRoute\RouteCollector $r) {
                $r->addRoute('GET', '/do-something', 'handler');
                $r->addRoute('GET', '/do-another-thing', 'handler');
                $r->addRoute('GET', '/do-something-else', 'handler');
            });
        });

    }
}
