<?php


namespace module\Internet;


use helper\Env;
use helper\WebServer\Server;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use Inhere\Console\Util\Show;

class Service
{
    private $input;
    private $output;
    private $runtime = ['service' => 'Swoole HTTP', 'host' => '0.0.0.0', 'port' => 0, 'status' => false, 'pid' => '-'];

    public function __construct(Input $input, Output $output)
    {
        $this->input = $input;
        $this->output = $output;

        if (!is_dir(ROOT_PATH . '/runtime')) {
            mkdir(ROOT_PATH . '/runtime', 0777);
        }


        if (is_file(ROOT_PATH . '/runtime/webserver.pid')) {
            try {
                $this->runtime = unserialize(file_get_contents(ROOT_PATH . '/runtime/webserver.pid'));
            } catch (\Error $exception) {
            } catch (\ErrorException $exception) {
            } catch (\Exception $exception) {
            }
        }
    }

    public function pushConfig()
    {
        file_put_contents(ROOT_PATH . '/runtime/webserver.pid', serialize($this->runtime));
    }

    public function start()
    {
        $host = Env::get('HTTP.HOST','0.0.0.0');
        $port = Env::get('HTTP.PORT','8008');
        $this->output->info("Start Web Server ...");

        $force = $this->input->getOption('force',false);

        if ($this->runtime['status'] !== false && $force == false) {
            $this->output->error("Network not stopped!!!");
            return;
        }

        if ($force == true){
            $this->output->warning("应用网络强制启动!");
        }

        $process = new \Swoole\Process(function () use ($host, $port) {
            new Server($host, $port);
        });
        $pid = $process->start();

        if ($pid === false) {
            $this->output->error("Service startup failure!!!");
        }

        $this->runtime = ['service' => 'Swoole HTTP', 'host' => $host, 'port' => $port, 'status' => $pid, 'pid' => $pid !== false ? $pid : '-'];

        $runtime_table[] = ['service' => 'Swoole HTTP', 'host' => "{$host}", 'port' => "{$port}", 'status' => $pid === false ? 'fail' : 'successful', 'pid' => $pid !== false ? "{$pid}" : '-'];

        Show::table($runtime_table, 'Service Table');

        $this->pushConfig();

        //$this->output->table($runtime_table);
    }

    public function stop()
    {

        if ($this->runtime['status'] === false){
            $this->output->error("Network not started!!!");
            return;
        }

        try {
            $ret = true;
            if (\Swoole\Process::kill(intval($this->runtime['pid']),0)){
                $ret = \Swoole\Process::kill(intval($this->runtime['pid']));
            }
        } catch (\Throwable $exception){
            $this->output->writeln('抓到错误');
        }

        if ($ret) {
            delectFileAll(ROOT_PATH.'/public');
            $this->runtime['status'] = false;
            $this->runtime['pid'] = '-';
        }
        $this->pushConfig();
    }

    public function reload()
    {

    }

    public function status()
    {

    }
}
