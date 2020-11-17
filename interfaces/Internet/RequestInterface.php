<?php


namespace interfaces\Internet;


use interfaces\Internet\Request\CookieInterface;
use interfaces\Internet\Request\FileInterface;
use interfaces\Internet\Request\GetInterface;
use interfaces\Internet\Request\HeaderInterfaces;
use interfaces\Internet\Request\PostInterface;
use interfaces\Internet\Request\ServerInterface;

interface RequestInterface
{
    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getUri();

    /**
     * @return HeaderInterfaces
     */
    public function getHeader();

    /**
     * @return ServerInterface
     */
    public function getServer();

    /**
     * @return CookieInterface
     */
    public function getCookie();

    /**
     * @return GetInterface
     */
    public function getGet();

    /**
     * @return PostInterface
     */
    public function getPost();

    /**
     * @return FileInterface
     */
    public function getFiles();
}
