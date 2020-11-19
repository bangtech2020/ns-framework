<?php


namespace app\bangtech\swoole_http\event;


use app\bangtech\swoole_http\webServer\Service;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;

class EventListener
{
    private $input;
    private $output;

    public function __construct(Input $input, Output $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * App启动之前事件
     */
    public function app_start_before() :void
    {

    }

    /**
     * App启动事件
     */
    public function app_start() :void
    {
        (new Service($this->input,$this->output))->start('0.0.0.0',8008);
    }


    /**
     * App启动之后事件
     */
    public function app_start_after() :void
    {

    }


    /**
     * App停止之前事件
     */
    public function app_stop_before() :void
    {

    }

    /**
     * App停止事件
     */
    public function app_stop() :void
    {
        (new Service($this->input,$this->output))->stop();
    }


    /*
     * App停止之后事件
     */
    public function app_stop_after() :void
    {

    }
}
