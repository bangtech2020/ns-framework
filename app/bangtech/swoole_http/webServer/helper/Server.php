<?php


namespace app\bangtech\swoole_http\webServer\helper;

use app\bangtech\swoole_http\webServer\interfaces\ServerInterface;

abstract class Server implements ServerInterface
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
}
