<?php


namespace helper\Internet;


use interfaces\Internet\HttpInterface;

abstract class Server implements HttpInterface
{

    protected $on = ['onStart', 'onShutdown', 'onConnect', 'onClose'];
    protected $options = [
        'task_worker_num' => 4
    ];

    public function __construct()
    {
        $this->start();
    }
}
