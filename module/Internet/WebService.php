<?php


namespace module\Internet;


use interfaces\Internet\ResponseInterface;
use interfaces\Internet\RequestInterface;
use module\Internet\WebServer\Route;

class WebService
{

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var RequestInterface
     */
    private $request;


    public function __construct(RequestInterface $request,ResponseInterface $response)
    {
        $this->response = $response;
        $this->request  = $request;
    }

    public function onRequest()
    {
        $routeInfo = Route::dispatch($this->request->getMethod(), $this->request->getUri());
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $this->response->setStatus(404);
                $this->response->end('404 Not Found');
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $this->response->setStatus(405);
                $this->response->end('405 Method Not Allowed');
                break;
            case \FastRoute\Dispatcher::FOUND:
                [$class, $action] = explode('@', $routeInfo[1]);
                try {
                    $class_instance = new $class($this->request,$this->response);
                    $result = call_user_func([$class_instance, $action]);
                    if (!empty($result)) {
                        $this->response->setStatus(200);
                        $this->response->end($result);
                    }
                } catch (\Exception $exception) {
                    $this->response->setStatus(500);
                    $this->response->end('500 Internal Server Error');
                    var_dump("\033[1;31m500 Internal Server Error => {$exception->getMessage()}\033[0m");
                    //$this->output->writeln("\033[1;31m500 Internal Server Error => {$exception->getMessage()}\033[0m");
                    return;
                }
                break;
        }
    }
}
