<?php


namespace helper\Console;


use Inhere\Console\Controller;

abstract class CommandGroupInterface extends Controller
{
    final public static function resetName($prefix)
    {
        self::$name = $prefix.self::$name;
    }
}
