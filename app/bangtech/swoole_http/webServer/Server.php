<?php


namespace app\bangtech\swoole_http\webServer;


use bootstrap\autoload;
use helper\Di;
use helper\WebServer\Route;
use helper\WebServer\WebService;
use interfaces\Console\OutputInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

class Server extends \helper\WebServer\Server
{

    /**
     * @inheritDoc
     */
    public function onStart(\Swoole\Server $server, ...$args)
    {
    }

    /**
     * @inheritDoc
     */
    public function onShutdown(\Swoole\Server $server, ...$args)
    {
    }

    /**
     * @inheritDoc
     */
    public function onConnect(\Swoole\Server $server, int $fd, int $reactorId, ...$args)
    {
    }

    /**
     * @inheritDoc
     */
    public function onClose(\Swoole\Server $server, int $fd, int $reactorId, ...$args)
    {
    }

    /**
     * @inheritDoc
     */
    public function onRequest(Request $request, Response $response, ...$args)
    {
        $webService = new WebService(new \helper\WebServer\Request($request), new \helper\WebServer\Response($response));
        $webService->onRequest();
    }

    /**
     * @inheritDoc
     */
    public function onTask(\Swoole\Server $server, int $task_id, int $src_worker_id, $data)
    {
    }
}
