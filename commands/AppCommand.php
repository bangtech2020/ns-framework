<?php


namespace commands;

use Inhere\Console\IO\Input;
use Inhere\Console\IO\InputDefinition;
use Inhere\Console\IO\Output;
use interfaces\Console\CommandGroupInterface;

class AppCommand extends CommandGroupInterface
{
    protected static $name = 'app';
    protected static $description = 'Application development tool';

    public function addCommand()
    {
        $this->write('hello, welcome!!');
    }
}
