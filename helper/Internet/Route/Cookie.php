<?php


namespace helper\Internet\Route;


use interfaces\Route\CookieInterface;

class Cookie extends Param implements CookieInterface
{

    public function set($name, $value)
    {
        $this->params[$name] = $value;
        return true;
    }
}
