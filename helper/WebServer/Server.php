<?php


namespace helper\WebServer;

use Swoole\Http\Request;
use Swoole\Http\Response;

class Server
{

    protected $host = '';
    protected $port = 0;
    protected $on = ['Start', 'Shutdown', 'Connect', 'Close','Request','Task'];
    protected $options = [
        'task_worker_num' => 4
    ];

    public function __construct(string $host, string $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->start();
    }

    public function start()
    {
        $server = new \Swoole\Http\Server($this->host, $this->port);
        //设置参数
        $server->set($this->options);

        foreach ($this->on as $key => $value) {
            if (method_exists($this, "on{$value}")) {
                $server->on($value, [$this, "on{$value}"]);
            }
        }
        $server->start();
    }

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
        $webService = new WebService(new \helper\WebServer\Request($request),new \helper\WebServer\Response($response));
        $webService->onRequest();
    }

    /**
     * @inheritDoc
     */
    public function onTask(\Swoole\Server $server, int $task_id, int $src_worker_id, $data)
    {
    }
}
