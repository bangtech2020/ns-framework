<?php
class a{
    public function b()
    {
        $scheduler = new \Swoole\Coroutine\Scheduler();
        $scheduler->add(function () {
            \Swoole\Coroutine::create(function () {
                $i = 0;
                while (true) {
                    var_dump("hello - " . $i++);
                    \Swoole\Coroutine::sleep(0.1);
                }
            });

            \Swoole\Coroutine::create(function () {
                $i = 0;
                while (true) {
                    var_dump("world - " . $i++);
                    \Swoole\Coroutine::sleep(0.5);
                }
            });
        });
        $scheduler->set(['max_coroutine' => 100]);
        $scheduler->start();
    }
}

(new a())->b();

var_dump("+++++++++++++++++++++++++++++++++++++++++++++++++++++++");
