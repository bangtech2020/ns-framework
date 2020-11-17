<?php


namespace app\bangtech\demo\extend;


class Index extends \helper\Internet\Controller
{
    public function index()
    {
        var_dump("hello");
        return "hello one";
    }
}
