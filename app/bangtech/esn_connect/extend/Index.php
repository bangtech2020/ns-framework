<?php
declare(strict_types=1);

namespace app\bangtech\esn_connect\extend;


class Index extends \helper\Internet\Controller
{
    public function index()
    {
        \helper\Di::getContainer()->get(\interfaces\Console\OutputInterface::class)->writeln("hello");
        $this->response->write("hello one");
    }
}
