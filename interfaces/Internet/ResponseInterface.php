<?php


namespace interfaces\Internet;


interface ResponseInterface
{
    public function setHeader(string $key, string $value);
    public function setCookie(string $key, string $value = '', int $expire = 0 , string $path = '/', string $domain  = '', bool $secure = false);
    public function setStatus(int $http_status_code);
    public function redirect(string $url, int $http_code = 302);
    public function write(string $data);
    public function sendfile(string $filename, int $offset = 0, int $length = 0);
    public function end(string $html);

}
