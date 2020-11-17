<?php


namespace module\Internet\WebServer;

use helper\Internet\Request\Cookie;
use helper\Internet\Request\File;
use helper\Internet\Request\Get;
use helper\Internet\Request\Header;
use helper\Internet\Request\Post;
use helper\Internet\Request\Server;


class Request extends \helper\Internet\Request
{
    public function __construct(\Swoole\Http\Request $request)
    {
        $this->method = $request->server['request_method'];
        $this->uri = $request->server['request_uri'];
        $this->header = new Header($request->header);
        $this->cookie = new Cookie($request->cookie);
        $this->server = new Server($request->server);
        $this->get = new Get($request->get);
        $this->post = new Post($request->post);
        $files = [
            'name' => isset($request->files['name'])?:'',
            'type' => isset($request->files['type'])?:'',
            'tmp_name' => isset($request->files['tmp_name'])?:'',
            'size' => isset($request->files['size'])?:'',
        ];
        $this->files = new File($files['name'], $files['type'], $files['tmp_name'], $files['size']);
        $this->request = $request;
    }
}
