<?php

declare(strict_types=1);

namespace helper\Internet;


use helper\Internet\Request\Cookie;
use helper\Internet\Request\File;
use helper\Internet\Request\Get;
use helper\Internet\Request\Header;
use helper\Internet\Request\Post;
use helper\Internet\Request\Server;
use interfaces\Internet\RequestInterface;

abstract class Request implements RequestInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_HEAD = 'HEAD';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_TRACE = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';

    /**
     * @var string
     */
    protected $method;
    /**
     * @var string
     */
    protected $uri;
    /**
     * @var Header
     */
    protected $header;
    /**
     * @var Server
     */
    protected $server;
    /**
     * @var Cookie
     */
    protected $cookie;
    /**
     * @var Get
     */
    protected $get;
    /**
     * @var Post
     */
    protected $post;
    /**
     * @var File
     */
    protected $files;

    protected $request;



    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }


    /**
     * @return Header|\interfaces\Internet\Request\HeaderInterfaces
     */
    public function getHeader()
    {
        return $this->header;
    }


    /**
     * @return Server|\interfaces\Internet\Request\ServerInterface
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return Cookie|\interfaces\Internet\Request\CookieInterface
     */
    public function getCookie()
    {
        return $this->cookie;
    }


    /**
     * @return Get|\interfaces\Internet\Request\GetInterface
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @return Post|\interfaces\Internet\Request\PostInterface
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return File|\interfaces\Internet\Request\FileInterface
     */
    public function getFiles(): File
    {
        return $this->files;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

}
