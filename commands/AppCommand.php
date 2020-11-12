<?php


namespace commands;

use Inhere\Console\Component\Formatter\HelpPanel;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\InputDefinition;
use Inhere\Console\IO\Output;
use helper\Console\CommandGroupInterface;
use helper\Console\CommandInterface;
use Inhere\Console\Util\Show;

class AppCommand extends CommandInterface
{

    protected static $name = 'app';
    protected static $description = 'Application development tool';

    /**
     * @inheritDoc
     */
    protected function execute($input, $output)
    {
        HelpPanel::show([
            HelpPanel::DESC => '请使用app:create 创建应用 或者 app:pack 打包应用',
            HelpPanel::USAGE => 'app:{action} [action]=>create|pack'
        ]);
    }
}
