<?php


namespace commands\App;


use FilesystemIterator;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use helper\Console\CommandInterface;
use Phar;

class PackCommand extends CommandInterface
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

        $exts = ['php'];// 需要打包的文件后缀, twig是模版文件, 你还可以安需加入html等后缀
        [$author,$identification] =  explode('/',$app_path);

        $dir = ROOT_PATH."/app/{$app_path}";// 需要打包的目录
        $out_dir = ROOT_PATH."/app/{$author}";// 需要打包的目录

        if (!is_dir($dir)){
            $this->output->error("Folder [{$dir}] does not exist!!!");
            return;
        }

        if (is_file("{$out_dir}/{$identification}.phar")){
            unlink("{$out_dir}/{$identification}.phar");
        }

        // 包的名称, 注意它不仅仅是一个文件名, 在stub中也会作为入口前缀
        $phar = new Phar("{$out_dir}/{$identification}.phar", FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, "{$identification}.phar");

        // 开始打包
        $phar->startBuffering();


        // 将后缀名相关的文件打包
        $phar->buildFromDirectory($dir, "/.*/");

        $phar->stopBuffering();


    }
}
