<?php


namespace app\bangtech\swoole_http\event;


use app\bangtech\swoole_http\webServer\Service;
use helper\Di;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use interfaces\Console\InputInterface;
use interfaces\Console\OutputInterface;

class EventListener
{
    private $input;
    private $output;

    public function __construct()
    {
        $this->input = Di::getContainer()->get(InputInterface::class);
        $this->output = Di::getContainer()->get(OutputInterface::class);
    }

    /**
     * App启动之前事件
     */
    public function app_start_before($context) :void
    {

    }

    /**
     * App启动事件
     */
    public function app_start($context) :void
    {
        (new Service($this->input,$this->output))->start('0.0.0.0',8008);
    }


    /**
     * App启动之后事件
     */
    public function app_start_after($context) :void
    {

    }


    /**
     * App停止之前事件
     */
    public function app_stop_before($context) :void
    {

    }

    /**
     * App停止事件
     */
    public function app_stop($context) :void
    {
        (new Service($this->input,$this->output))->stop();
    }


    /*
     * App停止之后事件
     */
    public function app_stop_after($context) :void
    {

    }
}
