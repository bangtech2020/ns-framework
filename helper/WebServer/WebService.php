<?php


namespace helper\WebServer;


use helper\Di;
use interfaces\Console\OutputInterface;
use interfaces\Internet\ResponseInterface;
use interfaces\Internet\RequestInterface;

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
                Di::getContainer()->get(OutputInterface::class)->warning('请求类型['.$this->request->getMethod().'] URI['.$this->request->getUri()."] 请检查是否app:load注册");
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
                } catch (\RuntimeException $exception){

                } catch (\Throwable $exception) {
                    $this->response->setStatus(500);
                    $this->response->end('500 Internal Server Error');
                    Di::getContainer()->get(OutputInterface::class)->error("500 Internal Server Error \nCode:{$exception->getCode()} Line:{$exception->getLine()} \n{$exception->getMessage()} \n{$exception->getTraceAsString()}");
                    return;
                }
                break;
        }
    }
}
