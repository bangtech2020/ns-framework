<?php


namespace helper\WebServer;

use bootstrap\autoload;
use extend\Table\Table;
use helper\Config;
use helper\Di;
use interfaces\Console\OutputInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Server
{

    protected $host = '';
    protected $port = 0;
    protected $on = ['Start', 'Shutdown', 'Connect', 'Close', 'Request', 'Task', 'WorkerStart'];
    protected $options = [
        'task_worker_num' => 4,
        'document_root'=>ROOT_PATH.'/public',
        'enable_static_handler'=>true
    ];

    public function __construct(string $host, string $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->start();
    }

    public function start()
    {
        $server = new \Swoole\Http\Server($this->host, $this->port);
        //设置参数
        $server->set($this->options);

        //如果在开发环境框架自动进行文件辩护的reload
        if (Config::get('app.dev') === true) {
            $server->addProcess($this->addProcess($server));
        }


        foreach ($this->on as $key => $value) {
            if (method_exists($this, "on{$value}")) {
                $server->on($value, [$this, "on{$value}"]);
            }
        }
        $server->start();
    }

    /**
     * @inheritDoc
     */
    public function onStart(\Swoole\Server $server, ...$args)
    {
    }


    /**
     * @param \Swoole\Server $server
     * @param int $workerId
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function onWorkerStart(\Swoole\Server $server, int $workerId)
    {
        autoload::reload();
        Route::reload();
    }

    /**
     * @inheritDoc
     */
    public function onShutdown(\Swoole\Server $server, ...$args)
    {
    }

    /**
     * @inheritDoc
     */
    public function onConnect(\Swoole\Server $server, int $fd, int $reactorId, ...$args)
    {
    }

    /**
     * @inheritDoc
     */
    public function onClose(\Swoole\Server $server, int $fd, int $reactorId, ...$args)
    {
    }

    /**
     * @inheritDoc
     */
    public function onRequest(Request $request, Response $response, ...$args)
    {
        $webService = new WebService(new \helper\WebServer\Request($request),new \helper\WebServer\Response($response));
        $webService->onRequest();
    }

    /**
     * @inheritDoc
     */
    public function onTask(\Swoole\Server $server, int $task_id, int $src_worker_id, $data)
    {
    }

    /**
     * @param \Swoole\Server $server
     * @return \Swoole\Process
     */
    public function addProcess(\Swoole\Server $server)
    {
        return new \Swoole\Process(function (\Swoole\Process $process) use ($server) {

            $table = new Table();

            $table->column('path_name', Table::TYPE_STRING, true);
            $table->column('has_file', Table::TYPE_STRING, true);

            $finder = new Finder();
            $has_files = [];

            foreach ($finder->in(ROOT_PATH.'/app') as $index => $file) {
                /**
                 * @var SplFileInfo $file
                 */

                if ($file->isFile()) {
                    $path_name = $file->getPathname();
                    $has_file = [
                        'path_name' => $path_name,
                        'has_file' => md5_file($path_name),
                    ];
                    $has_files[] = $has_file;
                    $table->insert($has_file);
                }
            }

            //处理完成后注销，防止内存泄露
            unset($finder);

            $has_reload = false;

            while (!$has_reload) {
                $has_reload = $this->checkReload(count($has_files), $table, ROOT_PATH.'/app');
                if ($has_reload) {
                    $server->reload();
                } else {
                    \Swoole\Coroutine::sleep(0.2);
                }
            }
        }, false, 2, true);
    }


    /**
     * @param $has_files_count
     * @param Table $table
     * @param array $manage_path
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected function checkReload($has_files_count, $table, $manage_path)
    {
        $finder = new Finder();
        $files = $finder->in($manage_path);
        $has_files = [];

        foreach ($files as $index => $file) {
            /**
             * @var SplFileInfo $file
             */
            if ($file->isFile()) {
                $path_name = $file->getPathname();
                $has_file = [
                    'path_name' => $path_name,
                    'has_file' => md5_file($path_name),
                ];
                $has_files[] = $has_file;

                $info = $table->find('path_name', $path_name);
                if ($info === false) {
                    Di::getContainer()->get(OutputInterface::class)->writeln("有新的文件：{$path_name}");
                    Di::getContainer()->get(OutputInterface::class)->writeln("将重新加载全部文件");
                    return true;
                }
                if ($info['has_file'] != $has_file['has_file']) {
                    Di::getContainer()->get(OutputInterface::class)->writeln("文件【{$path_name}】发生变化了：{$info['has_file']} => {$has_file['has_file']}");
                    return true;
                }
            }
        }

        if ($has_files_count !== count($has_files)) {
            Di::getContainer()->get(OutputInterface::class)->writeln("文件数量发生变化更新,历史数量【{$has_files_count}】,现在数量【" . count($has_files) . "】");
            return true;
        }

        return false;
    }
}
