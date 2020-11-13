<?php


namespace helper\Internet;

abstract class WebServer extends Server
{
    protected $on = ['Start', 'Shutdown', 'Connect', 'Close', 'Request'];

    /**
     * @inheritDoc
     */
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
