<?php


namespace app\bangtech\swoole_http\command\WebServer\interfaces;


use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server;

interface ServerInterface
{

    public function start();

    /**
     * @param Server $server Swoole\Server对象
     * @param array $args
     */
    public function onStart(Server $server, ...$args);

    /**
     * @param Server $server Swoole\Server对象
     * @param array $args
     */
    public function onShutdown(Server $server, ...$args);

    /**
     * @param Server $server Swoole\Server对象
     * @param int $fd 连接的文件描述符`
     * @param int $reactorId 连接所在的 Reactor 线程 ID
     * @param array $args
     */
    public function onConnect(Server $server, int $fd, int $reactorId, ...$args);

    /**
     * @param Server $server Swoole\Server对象
     * @param int $fd 连接的文件描述符
     * @param int $reactorId 来自那个reactor线程，主动close关闭时为负数
     * @param array $args
     */
    public function onClose(Server $server, int $fd, int $reactorId, ...$args);


    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function onRequest(Request $request, Response $response, ...$args);

    /**
     * @param Server $server
     * @param int $task_id
     * @param int $src_worker_id
     * @param $data
     * @return mixed
     */
    public function onTask(Server $server, int $task_id, int $src_worker_id, $data);
}
