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
     */
    public function __construct($method, $uri, $header, $server, $cookie, $get, $post, $files)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->header = $header;
        $this->cookie = $cookie;
        $this->server = $server;
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;



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
