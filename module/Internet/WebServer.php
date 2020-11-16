<?php


namespace module\Internet;


use helper\Internet\Request\Cookie;
use helper\Internet\Request\Get;
use helper\Internet\Request\Header;
use helper\Internet\Request\Post;
use helper\Internet\Request\UploadFile;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server;

class WebServer extends \helper\Internet\WebServer
{
    /**
     * @inheritDoc
     */
    public function onStart(Server $server, ...$args)
    {
        // TODO: Implement onStart() method.
    }

    /**
     * @inheritDoc
     */
    public function onShutdown(Server $server, ...$args)
    {
        // TODO: Implement onShutdown() method.
    }

    /**
     * @inheritDoc
     */
    public function onConnect(Server $server, int $fd, int $reactorId, ...$args)
    {
        // TODO: Implement onConnect() method.
    }

    /**
     * @inheritDoc
     */
    public function onClose(Server $server, int $fd, int $reactorId, ...$args)
    {
        // TODO: Implement onClose() method.
    }

    /**
     * @inheritDoc
     */
    public function onRequest(Request $request, Response $response, ...$args)
    {

        $header = $request->header?:[];
        $server = $request->server?:[];
        $cookie = $request->cookie?:[];
        $get = $request->get?:[];
        $post = $request->post?:[];

        new \helper\Internet\Request(
            $request->server['request_method'],
            $request->server['request_uri'],
            new Header($header),
            new \helper\Internet\Request\Server($server),
            new Cookie($cookie),
            new Get($get),
            new Post($post),
            $this->getUploadFile($request->files),
            new \module\Internet\WebServer\Request($response)
        );
    }


    private function getUploadFile($files)
    {
        $name = '';
        $type = '';
        $tmp_name = '';
        $error = '';
        $size = '';

        if ($files){
            $name = $files['name'];
            $type = $files['type'];
            $tmp_name = $files['tmp_name'];
            $error = $files['error'];
            $size = $files['size'];
        }
        return new UploadFile($name,$type,$tmp_name,$error,$size);
    }

    /**
     * @inheritDoc
     */
    public function onTask(Server $server, int $task_id, int $src_worker_id, $data)
    {
        // TODO: Implement onTask() method.
    }
}
