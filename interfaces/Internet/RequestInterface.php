<?php


namespace interfaces\Internet;


interface RequestInterface
{
    public function __construct($header = [], $server = [], $cookie= [], $get = [], $files = [],$post = [],$tmpfiles = []);
}
