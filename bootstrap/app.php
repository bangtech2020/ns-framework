<?php

declare(strict_types=1);

namespace bootstrap;

use helper\Config;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use Inhere\Console\Application;
use helper\Console;
use Phar;

class app
{

    private $config = [
        'text_logo' => '',
        'base_path' => '',
        'root_path' => '',
    ];

    public function __construct()
    {
        !defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));
        //确定是否在Phar包里面
        if (preg_match('/^phar:\/\/.*/', __FILE__)) {
            !defined('ROOT_PATH') && define('ROOT_PATH', dirname(Phar::running(false), 1));
            !defined('HAS_DEV') && define('HAS_DEV', false);
        } else {
            !defined('ROOT_PATH') && define('ROOT_PATH', dirname(__DIR__, 1));
            !defined('HAS_DEV') && define('HAS_DEV', true);
        }
        $this->config['base_path'] = BASE_PATH;
        $this->config['root_path'] = ROOT_PATH;

        date_default_timezone_set('Asia/Shanghai');
        $this->config['text_logo'] = file_get_contents($this->config['base_path'] . '/brand/ns_logo.text');
        Config::__make(BASE_PATH.'/config','php');
    }

    public function start()
    {

        $meta = [
            'name' => 'NS Framework',
            'version' => get_git(),
            'description' => 'NS development Framework,all plug-ins are loaded by Phar',
            'rootPath' => dirname(__DIR__)
        ];

        $input = new Input;
        $output = new Output;

        // 通常无需传入 $input $output ，会自动创建
        $app = new Application($meta, $input, $output);
        $app->setLogo($this->getTextLogo(), 'success');

        //注册系统命令集
        $commands = Config::get('commands');

        foreach ($commands as $key => $command) {
            [$class, $type] = $command;
            if ($type === Console::HAS_GROUP) {
                $app->controller($class);
            } else {
                $app->command($class);
            }

        }

        $app->run();
    }

    public function getTextLogo()
    {
        return $this->config['text_logo'];
    }
}
