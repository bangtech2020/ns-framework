<?php
$str = 'abcc\\\\\\sss\ss';
var_dump($str);
$st = preg_replace('/(\\\\+)+/', '/', $str);

var_dump($st);
