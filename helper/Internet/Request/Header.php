<?php


namespace helper\Internet\Request;


use interfaces\Request\HeaderInterfaces;

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
