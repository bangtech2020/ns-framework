<?php


namespace commands\Server;


use helper\Console\CommandInterface;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use module\Internet\WebServer;

class InternetCommand extends CommandInterface
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
