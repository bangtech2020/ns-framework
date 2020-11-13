<?php


namespace helper\Internet;


use interfaces\Internet\HttpInterface;

abstract class Server implements HttpInterface
{

    protected $host = '';
    protected $port = 0;
    protected $on = ['onStart', 'onShutdown', 'onConnect', 'onClose'];
    protected $options = [
        'task_worker_num' => 4
    ];

    public function __construct(string $host, string $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->start();
    }
}
