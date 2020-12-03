<?php
declare(strict_types=1);

namespace commands;

use helper\Event\Event;
use Inhere\Console\Component\Formatter\HelpPanel;
use Inhere\Console\Contract\InputInterface;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\InputDefinition;
use Inhere\Console\IO\Output;
use helper\Console\CommandGroup;
use helper\Console\Command;

class AppCommand extends Command
{
    protected static $name = 'app';
    protected static $description = 'Application development tool';



    protected function configure(): void
    {
        $this->createDefinition()
            ->addArgument('status',Input::ARG_REQUIRED,'应用工作状态 [start|stop|status|reload]')
            ->addOption('force', 'f', Input::OPT_BOOLEAN,'是否强制启动');
        ;

    }

    /**
     * @inheritDoc
     */
    protected function execute($input, $output)
    {
        $status = $input->getArgument('status');

        switch ($status){
            case 'start':
                Event::process('app_start',$this);
                break;
            case 'stop':
                Event::process('app_stop',$this);
                break;
            case 'status':
                Event::process('app_status',$this);
                break;
            case 'reload':
                Event::process('app_reload',$this);
                break;

        }
    }

}
