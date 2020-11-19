<?php


namespace app\bangtech\swoole_http\command;


use app\bangtech\swoole_http\webServer\Service;
use helper\Console\CommandInterface;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;

class WebServerCommand extends CommandInterface
{
    protected static $name = 'swoole_http';


    protected function configure(): void
    {
        parent::configure();
        $this->createDefinition()
            ->setExample($this->parseCommentsVars('{script} {command} [start|stop|reload|status]'))
            ->addArgument('status', Input::ARG_REQUIRED, 'action status [start|stop|reload|status]');
    }

    /**
     * @inheritDoc
     */
    protected function execute($input, $output)
    {
        $service = new Service($this->input, $this->output);
        switch ($input->getArgument('status')) {
            case 'start':
                $service->start('0.0.0.0', 8008);
                break;
            case 'stop':
                $service->stop();
                break;
            case 'reload':
                $service->reload();
                break;
            case 'status':
                $service->status();
                break;
            default:
                $this->output->warning("Incorrect instruction");
                break;

        }

    }
}
