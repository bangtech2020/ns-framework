<?php


namespace helper\Internet\Request;


use interfaces\Request\CookieInterface;

class Cookie extends Param implements CookieInterface
{

    public function set($name, $value)
    {
        $this->params[$name] = $value;
        return true;
    }
}