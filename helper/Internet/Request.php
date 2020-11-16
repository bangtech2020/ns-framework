<?php

declare(strict_types=1);

namespace helper\Internet;

use interfaces\Request\CookieInterface;
use interfaces\Request\GetInterface;
use interfaces\Request\HeaderInterfaces;
use interfaces\Request\PostInterface;
use interfaces\Request\ServerInterface;
use interfaces\Request\UploadFileInterface;
use interfaces\RouteInterface;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_HEAD = 'HEAD';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_TRACE = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';

    protected $method;
    protected $uri;
    protected $header;
    protected $server;
    protected $cookie;
    protected $get;
    protected $post;
    protected $files;
    protected $request;

    /**
     * Route constructor.
     * @param string $method
     * @param string $uri
     * @param HeaderInterfaces $header
     * @param ServerInterface $server
     * @param CookieInterface $cookie
     * @param GetInterface $get
     * @param PostInterface $post
     * @param UploadFileInterface $files
     * @param \module\Internet\WebServer\Request $request
     */
    public function __construct($method, $uri, $header, $server, $cookie, $get, $post, $files, $request)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->header = $header;
        $this->cookie = $cookie;
        $this->server = $server;
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
        $this->request = $request;
        $this->route();
    }


    public function route()
    {
        $routeInfo = Route::dispatch($this->method, $this->uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $this->request->send(404, '404 Not Found');
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $this->request->send(405, '405 Method Not Allowed');
                break;
            case \FastRoute\Dispatcher::FOUND:
                //$handler = $routeInfo[1];
                //$vars = $routeInfo[2];

                [$class, $action] = explode('@', $routeInfo[1]);
                var_dump($routeInfo[1], $routeInfo[2]);
                var_dump($class, $action);
                try {
                    $class_instance = new $class();
                    $result = call_user_func([$class_instance, $action]);
                    if (!empty($result)) {
                        $this->request->send('200',$result);
                    }
                } catch (\Exception $exception) {
                    $this->request->send(500,'500 Internal Server Error');
                    var_dump("\033[1;31m500 Internal Server Error => {$exception->getMessage()}\033[0m");
                    //$this->output->writeln("\033[1;31m500 Internal Server Error => {$exception->getMessage()}\033[0m");
                    return;
                }
                break;
        }
    }


    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return HeaderInterfaces
     */
    public function getHeader(): HeaderInterfaces
    {
        return $this->header;
    }

    /**
     * @return ServerInterface
     */
    public function getServer(): ServerInterface
    {
        return $this->server;
    }

    /**
     * @return CookieInterface
     */
    public function getCookie(): CookieInterface
    {
        return $this->cookie;
    }

    /**
     * @return GetInterface
     */
    public function getGet(): GetInterface
    {
        return $this->get;
    }

    /**
     * @return PostInterface
     */
    public function getPost(): PostInterface
    {
        return $this->post;
    }

    /**
     * @return UploadFileInterface
     */
    public function getFiles(): UploadFileInterface
    {
        return $this->files;
    }


}
