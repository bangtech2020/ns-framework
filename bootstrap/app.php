<?php

declare(strict_types=1);

namespace bootstrap;

use helper\Config;
use helper\Di;
use helper\Env;
use helper\Event\Listener;
use helper\WebServer\Route;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use Inhere\Console\Application;
use helper\Console;
use interfaces\Console\InputInterface;
use interfaces\Console\OutputInterface;
use InvalidArgumentException;
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

        $this->init();
    }

    /**
     * @throws \Exception
     */
    public function init()
    {
        //首先初始化容器
        Di::__make();
        Env::__make(ROOT_PATH . '/.env');
        Config::__make(BASE_PATH . '/config', 'php');
        load::__make(ROOT_PATH . "/app");
        Route::__make();
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

        Di::getContainer()->set(InputInterface::class,$input);
        Di::getContainer()->set(OutputInterface::class,$output);

        // 通常无需传入 $input $output ，会自动创建
        $app = new Application($meta, $input, $output);
        $app->setLogo($this->getTextLogo());

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

        $this->registerCommand($app,$input,$output);
        $this->registerEventListener($app,$input,$output);

        $app->run();
    }

    /**
     * 注册命令
     * @param Application $app
     * @param Input $input
     * @param Output $output
     */
    public function registerCommand(\Inhere\Console\Application $app,Input $input,Output $output)
    {
        //注册APP命令
        $extend_commands = load::getCommands();

        foreach ($extend_commands as $id => $extend_command) {

            [$author,$identification] = explode('/',$id);
            foreach ($extend_command as $value) {
                $class =  'app\\'.$author . '\\'. $identification . '\\' . $value['class'];
                $type = $value['mode'];
                try {
                    if ($type == 'HAS_GROUP') {
                        $app->controller($class);
                    } else {
                        $app->command($class);
                    }
                }
                catch (InvalidArgumentException $exception){
                    $output->warning('加载失败:'.$exception->getMessage());
                } catch (\ErrorException $exception){
                    $output->warning('加载失败:'.$exception->getMessage());
                } catch (\Error $exception){
                    $output->warning('加载失败:'.$exception->getMessage());
                } catch (\Exception $exception){
                    $output->warning('加载失败:'.$exception->getMessage());
                }

            }
        }
    }

    /**
     * 注册事件监听
     * @param Application $app
     * @param Input $input
     * @param Output $output
     */
    public function registerEventListener(\Inhere\Console\Application $app,Input $input,Output $output)
    {

        Di::getContainer()->make(Listener::class);
        /**
         * @var Listener $listener
         */
        $listener = Di::getContainer()->get(Listener::class);

        $system_events = Config::get('event');

        foreach ($system_events as $event_name => $event_lists) {
            foreach ($event_lists as  $event) {
                //注册各类事件
                $listener->registered($event_name,new $event);
            }
        }

        //注册APP事件
        $extend_events = load::getEvents();
        foreach ($extend_events as $id => $extend_event) {
            [$author,$identification] = explode('/',$id);
            foreach ($extend_event as $event_name => $events) {
                //注册各类事件
                foreach ($events as  $event) {
                    $event =  'app\\'.$author . '\\'. $identification . '\\' . $event;
                    $listener->registered($event_name,$event);
                }
            }
        }
    }

    public function getTextLogo()
    {
        return $this->config['text_logo'];
    }
}
