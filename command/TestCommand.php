<?php


namespace command;

use Inhere\Console\Command;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;

class TestCommand
{
    // 命令名称
    protected static $name = 'test';
    // 命令描述
    protected static $description = 'this is a test independent command';

    /**
     * @usage usage message
     * @arguments
     *  arg     some message ...
     *
     * @options
     *  -o, --opt     some message ...
     *
     * @param Input $input
     * @param Output $output
     * @return void
     */
    public function execute($input, $output)
    {
        $output->write('hello, this in ' . __METHOD__);
    }
}
