<?php


namespace commands;


use helper\Console\CommandGroupInterface;

class HomeCommand extends CommandGroupInterface
{
    protected static $name = 'home';

    protected static $description = 'This is a demo command controller. there are some command usage examples(2)';


    /**
     * command `defArgCommand` config
     */
    protected function defArgConfigure(): void
    {
        $this->createDefinition()
            ->setDescription('the command arg/opt config use defined configure, it like symfony console: argument define by position')
            ->addArgument('name', Input::ARG_REQUIRED, "description for the argument 'name'")
            ->addOption('yes', 'y', Input::OPT_BOOLEAN, "description for the option 'yes'")
            ->addOption('opt1', null, Input::OPT_REQUIRED, "description for the option 'opt1'");
    }

    /**
     * the command arg/opt config use defined configure, it like symfony console: argument define by position
     */
    public function defArgCommand(): void
    {
        $this->output->dump($this->input->getArgs(), $this->input->getOpts(), $this->input->getBoolOpt('y'));
    }
}
