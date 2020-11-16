<?php


namespace helper\Internet\Route;


use interfaces\Route\HeaderInterfaces;

class Header extends Param implements HeaderInterfaces
{

    /**
     * @inheritDoc
     */
    public function set($name, $value)
    {
        $this->params[$name] = $value;
        return true;
    }
}
