<?php


namespace commands\App;

use helper\Console\CommandInterface;
use Inhere\Console\IO\Input;
use module\Internet\Service;

class WebServiceCommand extends CommandInterface
{

    protected static $name = 'app:web';
    protected static $description = 'Web Services management';

    public static function aliases(): array
    {
        return ['internet'];
    }


    protected function configure(): void
    {
        $this->createDefinition()
            ->setExample($this->parseCommentsVars('{script} {command} [start|stop|reload|status]'))
            ->addArgument('status', Input::ARG_REQUIRED, 'action status [start|stop|reload|status]');
    }

    /**
     * @inheritDoc
     */
    protected function execute($input, $output)
    {
        $status = $input->getArgument('status');
        $service = new Service($this->input, $this->output);
        switch ($status) {
            case 'start':
                $service->start();
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
