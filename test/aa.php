<?php
$str = 'abcc\\\\\\sss\ss';
var_dump($str);
$st = preg_replace('/(\$\{\{(\w+)\}\})+/', '/', $str);

var_dump($st);
