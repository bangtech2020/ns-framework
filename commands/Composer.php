<?php


namespace commands;


use helper\Console\Command;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;

class Composer extends Command
{

    protected static $name = 'composer';
    protected static $description = 'PHP Composer';

    protected function configure(): void
    {
        $this->createDefinition()
            ->addArgument('application',Input::ARG_REQUIRED,'Application name')
            ->addArgument('type',Input::ARG_REQUIRED,'[require|update]')
            ->addArgument('argument',Input::ARG_REQUIRED,'Composer packagist')
        ;

    }

    protected function execute($input, $output)
    {
        $application = $input->getArgument('application');
        $type        = $input->getArgument('type');
        $argument    = $input->getArgument('argument');


        $output->info($application);
        $output->info($type);
        $output->info($argument);

        $cmd = "composer {$type} {$argument}";
        $cwd = ROOT_PATH;
        var_dump("执行目录：{$cwd}");

        $spec = array(
            // can something more portable be passed here instead of /dev/null?
            0 => STDIN,
            1 => STDOUT,
            2 => STDERR,
        );

        $ph = proc_open($cmd, $spec, $pipes, $cwd);
        if ($ph === FALSE) {
            // open error

            $output->error('命令执行失败');
            return;
        }


        // If we are not passing /dev/null like above, we should close
        // our ends of any pipes to signal that we're done. Otherwise
        // the call to proc_close below may block indefinitely.


        if (is_resource($ph)){
            foreach ($pipes as $pipe) {
                @fclose($pipe);
            }
        }

        $status = proc_get_status($ph);
        // will wait for the process to terminate
        $exit_code = proc_close($ph);
        if ($exit_code !== 0) {
            // child error
            $output->error('执行错误');
            return;
        }

        //TOdo 执行成功写入app配置文件

    }
}