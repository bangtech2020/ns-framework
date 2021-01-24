<?php


namespace commands\App;


use FilesystemIterator;
use helper\Di;
use Inhere\Console\IO\Input;
use helper\Console\Command;
use Phar;
use Psr\Log\LoggerInterface;

class PackCommand extends Command
{

    protected static $name = 'app:pack';
    protected static $description = 'The application is packaged as a PHAR';

    protected function configure(): void
    {
        $this->createDefinition()
            ->addArgument('path',Input::ARG_REQUIRED,'{atuho}/{id}');
    }

    /**
     * @inheritDoc
     */
    protected function execute($input, $output)
    {

        $output->writeln("The application package");
        $app_path = $input->getArgument('path');

        [$author,$identification] =  explode('/',$app_path);

        $dir = ROOT_PATH."/app/{$app_path}";// 需要打包的目录
        $out_dir = ROOT_PATH."/app";// 打包的输出目录

        if (!is_dir($dir)){
            Di::getContainer()->get(LoggerInterface::class)->emergency("Folder [{dir}] does not exist!!!",['dir'=>$dir]);
            $this->output->error("Folder [{$dir}] does not exist!!!");
            return;
        }

        if (is_file("{$out_dir}/{$author}@{$identification}.phar")){
            unlink("{$out_dir}/{$author}@{$identification}.phar");
        }

        // 包的名称, 注意它不仅仅是一个文件名, 在stub中也会作为入口前缀
        $phar = new Phar("{$out_dir}/{$author}@{$identification}.phar", FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, "{$author}@{$identification}.phar");

        // 开始打包
        $phar->startBuffering();


        // 将后缀名相关的文件打包
        $phar->buildFromDirectory($dir, "/.*/");

        $phar->setSignatureAlgorithm(Phar::SHA512);
        $phar->stopBuffering();

        $output->success("Application package Succeed\r\nIdentification : {$author}@{$identification}\r\nOutput : {$out_dir}/{$author}@{$identification}.phar");
        Di::getContainer()
            ->get(LoggerInterface::class)
            ->notice("Application package Succeed\r\nIdentification : {author}@{identification}\r\nOutput : {out_dir}/{author}@{identification}.phar",
                [
                    'dir'=>$dir,
                    'out_dir'=>$out_dir,
                    'author'=>$author,
                    'identification'=>$identification
                ]
            );
    }
}
