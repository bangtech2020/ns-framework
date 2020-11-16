<?php


namespace commands\App;


use helper\Console\CommandInterface;

class LoadCommand extends CommandInterface
{

    protected static $name = 'app:load';
    protected static $description = 'Load an application';

    protected function configure(): void
    {
        $this->createDefinition();
    }

    /**
     * 更新{app}/app.json到ns.json和ns.lock
     * @inheritDoc
     */
    protected function execute($input, $output)
    {
        // TODO: Implement execute() method.
        $output->writeln("应用创建");
        //获得应用名称
        $appName = $this->read('Enter the application name: ');

        while (!$appName){
            $appName = $this->read('Please!!! Enter the application name: ');
        }

        $has_extension  = $this->confirm('Is there an extension? ');
        $has_plugin     = $this->confirm('Is there a plug-in? ');
        $has_command    = $this->confirm('Is there a command? ');
        $output->writeln("App Name: {$appName}");
    }
}
