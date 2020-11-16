<?php


namespace interfaces;


interface RouteInterface
{
    public function __construct($header = [], $server = [], $cookie= [], $get = [], $files = [],$post = [],$tmpfiles = []);
}
