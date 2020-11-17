<?php


namespace module\Internet\WebServer;


class Response extends \helper\Internet\Response
{
    private $response;

    public function __construct(\Swoole\Http\Response $response)
    {
        $this->response = $response;
    }

    public function setHeader(string $key, string $value)
    {
        $this->response->header($key, $value);
    }

    public function setCookie(string $key, string $value = '', int $expire = 0, string $path = '/', string $domain = '', bool $secure = false)
    {
        $this->response->cookie($key, $value, $expire, $path, $domain, $secure);
    }

    public function setStatus(int $http_status_code)
    {
        $this->response->status($http_status_code);
    }

    public function redirect(string $url, int $http_code = 302)
    {
        $this->response->redirect($url, $http_code);
    }

    public function write(string $data)
    {
        $this->response->write($data);
    }

    public function sendfile(string $filename, int $offset = 0, int $length = 0)
    {
        $this->response->sendfile($filename, $offset, $length);
    }

    public function end(string $html)
    {
        $this->response->end($html);
    }
}
