<?php


namespace helper\Internet;

use interfaces\Internet\RequestInterface;
use interfaces\Internet\ResponseInterface;

abstract class Controller
{
    protected $request;
    protected $response;
    public function __construct(RequestInterface $request,ResponseInterface $response)
    {
        $this->response = $response;
        $this->request = $request;
    }
}
