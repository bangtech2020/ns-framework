<?php


namespace commands\Server;


use helper\Console\Command;
use Inhere\Console\IO\Input;

class InternetCommand extends Command
{
    protected static $name = 'server:internet';
    protected static $description = 'Internet server management';

    public static function aliases(): array
    {
        return parent::aliases(); // TODO: Change the autogenerated stub
    }

    protected function configure(): void
    {
        parent::configure();
        $this->createDefinition()
            ->setExample($this->parseCommentsVars('{script} {command} [start|stop|reload|status]'))
            ->addArgument('status',Input::ARG_REQUIRED,'action status [start|stop|reload|status]')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute($input, $output)
    {
        $this->output->writeln("启动网络服务");
    }
}
