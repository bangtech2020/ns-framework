<?php
declare(strict_types=1);

namespace app\bangtech\hello\extend;


use helper\Di;
use interfaces\Console\OutputInterface;

class Index extends \helper\Internet\Controller
{
    public function index()
    {
        Di::getContainer()->get(OutputInterface::class)->writeln("hello");
        return "hello one";
    }
}
