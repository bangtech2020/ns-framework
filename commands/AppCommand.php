<?php


namespace commands;

use Inhere\Console\IO\Input;
use Inhere\Console\IO\InputDefinition;
use Inhere\Console\IO\Output;
use helper\Console\CommandGroupInterface;
use helper\Console\CommandInterface;

class AppCommand extends CommandInterface
{

    protected static $name = 'app';
    protected static $description = 'Application development tool';

    /**
     * @inheritDoc
     */
    protected function execute($input, $output)
    {
        $output->writeln("请使用app:create 创建应用 或者 app:pack 打包应用");
    }
}
