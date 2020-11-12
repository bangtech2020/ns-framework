<?php


namespace commands\app;


use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use interfaces\Console\CommandInterface;

class PackCommand extends CommandInterface
{

    protected static $name = 'app:pack';
    protected static $description = 'The application is packaged as a PHAR';

    /**
     * @inheritDoc
     */
    protected function execute($input, $output)
    {
        // TODO: Implement execute() method.
        $output->writeln("应用打包");
    }
}
