<?php


namespace app\bangtech\esn_connect\event;


use helper\Di;
use interfaces\Console\OutputInterface;

class EventListener
{

    public function app_start()
    {
        $socket = new \Swoole\Coroutine\Socket(AF_INET, SOCK_STREAM, 0);
        //创建协程容器
        $scheduler = new \Swoole\Coroutine\Scheduler();
        $scheduler->add(function (\Swoole\Coroutine\Socket $socket) {
            //创建通道书监听消息
            \Swoole\Coroutine::create(function () use ($socket){
                $retval = $socket->connect('127.0.0.1', 9601);
                while ($retval)
                {
                    $n = $socket->send("hello");
                    var_dump($n);

                    $data = $socket->recv();
                    var_dump($data);

                    if (empty($data)) {
                        //发生错误或对端关闭连接，本端也需要关闭
                        $socket->close();
                        break;
                    }
                    \Swoole\Coroutine::sleep(1.0);
                }
                var_dump($retval, $socket->errCode);
            });

            //创建用户交互数据监听
            \Swoole\Coroutine::create(function () {
                $i = 0;
                while (true) {
                    var_dump("world - " . $i++);
                    \Swoole\Coroutine::sleep(0.5);
                }
            });
        },$socket);
        $scheduler->start();
    }


    public function app_stop()
    {

    }

}
