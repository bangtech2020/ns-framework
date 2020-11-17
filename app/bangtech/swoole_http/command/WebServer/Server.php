<?php


namespace app\bangtech\swoole_http\command\WebServer;


use module\Internet\WebService;
use Swoole\Http\Request;
use Swoole\Http\Response;

class Server extends \app\bangtech\swoole_http\command\WebServer\helper\Server
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
        $webService = new WebService(new \module\Internet\WebServer\Request($request),new \module\Internet\WebServer\Response($response));
        $webService->onRequest();
    }

    /**
     * @inheritDoc
     */
    public function onTask(\Swoole\Server $server, int $task_id, int $src_worker_id, $data)
    {
    }
}
