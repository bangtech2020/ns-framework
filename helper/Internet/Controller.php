<?php


namespace helper\Internet;

use interfaces\Internet\RequestInterface;
use interfaces\Internet\ResponseInterface;

abstract class Controller
{
    protected $request;
    protected $response;

    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->response = $response;
        $this->request = $request;
    }

    protected function result($data, $code = 0, $msg = '', $type = 'json', array $header = [])
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'time' => time(),
            'data' => $data,
        ];

        foreach ($header as $index => $value) {
            $this->response->setHeader($index, $value);
        }

        $result = json_encode($result);
        $this->response->end($result);
        throw new \RuntimeException($result,0);
    }
}
