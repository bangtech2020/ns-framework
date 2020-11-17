<?php


namespace interfaces\Request;


interface UploadFileInterface
{
    public function __construct($name = '',$type = '',$tmp_name = '',$size = '');
    public function getName();
    public function getType();
    public function getTmpName();
    public function getSize();
}
