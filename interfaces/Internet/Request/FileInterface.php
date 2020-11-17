<?php


namespace interfaces\Internet\Request;


interface FileInterface
{
    public function __construct($name = '',$type = '',$tmp_name = '',$error = '',$size = '');
    public function getName();
    public function getType();
    public function getTmpName();
    public function getError();
    public function getSize();
}
