<?php

namespace eventListener;

use commands\AppCommand;
use helper\Di;
use interfaces\Console\InputInterface;
use interfaces\Console\OutputInterface;
use module\Internet\Service;

class AppStartEventListener
{
    /**
     * @param AppCommand $context
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function execute($context)
    {
        (new Service(Di::getContainer()->get(InputInterface::class), Di::getContainer()->get(OutputInterface::class)))->start();
    }
}
