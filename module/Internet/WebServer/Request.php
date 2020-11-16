<?php


namespace module\Internet\WebServer;


use Swoole\Http\Response;

class Request
{
    /**
     * @var Response
     */
    private $response;
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param string|int|double|float $msg
     */
    public function send($code ,$msg){
        $this->response->setStatusCode($code);
        $this->response->end($msg);
    }
}
