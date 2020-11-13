<?php

namespace commands;

use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use helper\Console\CommandInterface;

/**
 * Class TestCommand
 * @package commands
 */
class TestCommand extends CommandInterface
{
    // 命令名称
    protected static $name = 'test';
    // 命令描述
    protected static $description = 'this is a test independent command';


    protected function configure(): void
    {
        parent::configure(); // TODO: Change the autogenerated stub
        $this->createDefinition()
            ->addArgument('names',Input::ARG_REQUIRED,'name is admin');
    }


    /**
     * @param Input $input
     * @param Output $output
     * @return int|mixed|void
     */
    public function execute($input, $output)
    {

        $output->write('hello, this in ');
    }
}