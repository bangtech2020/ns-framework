<?php


namespace commands\App;


use commands\App\WebService\Service;
use helper\Console\CommandInterface;
use Inhere\Console\IO\Input;

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
