<?php
declare(strict_types=1);
namespace interfaces\Internet;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server;

interface HttpInterface extends NetworkInterface
{
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
}
