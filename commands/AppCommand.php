<?php
declare(strict_types=1);

namespace commands;

use Inhere\Console\Component\Formatter\HelpPanel;
use Inhere\Console\Contract\InputInterface;
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
        HelpPanel::show([
            HelpPanel::DESC => '请使用app:create 创建应用 或者 app:pack 打包应用',
            HelpPanel::USAGE => 'app:{action} [action]=>create|pack'
        ]);
    }


    /**
     * @var Input|InputInterface
     */
    public function createConfigure(): void
    {
        $this->createDefinition()
            ->setDescription('Create an application');
    }

    /**
     * @var Input|InputInterface
     */
    public function packConfigure(): void
    {
        $this->createDefinition()
            ->setDescription("The application is packaged as a PHAR");
    }


    public function createCommand(): void
    {
        $this->output->writeln("应用创建");
        //获得应用名称
        $appName = $this->read('Enter the application name: ');

        while (!$appName){
            $appName = $this->read('Please!!! Enter the application name: ');
        }

        $has_extension  = $this->confirm('Is there an extension? ');
        $has_plugin     = $this->confirm('Is there a plug-in? ');
        $has_command    = $this->confirm('Is there a command? ');
        $this->output->writeln("App Name: {$appName}");
    }

    public function packCommand(): void
    {
        $this->output->writeln("应用打包");
    }
}
