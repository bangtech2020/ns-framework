<?php

namespace helper;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class Log
 * @method LoggerInterface emergency($message, array $context = array()) static
 * @method LoggerInterface alert($message, array $context = array()) static
 * @method LoggerInterface critical($message, array $context = array()) static
 * @method LoggerInterface error($message, array $context = array()) static
 * @method LoggerInterface warning($message, array $context = array()) static
 * @method LoggerInterface notice($message, array $context = array()) static
 * @method LoggerInterface info($message, array $context = array()) static
 * @method LoggerInterface debug($message, array $context = array()) static
 * @package helper
 */
class Log extends AbstractLogger
{
    /**
     * 日志路径
     * @var string
     */
    protected $log_dir = '';

    public function __construct()
    {
        $this->log_dir = ROOT_PATH . '/runtime/log';
        //创建日志目录
        if (!is_dir("{$this->log_dir}")) {
            mkdir($this->log_dir, 755, true);
        }
    }

    public static function __callStatic($name, $arguments)
    {
        if (!Di::getContainer()->has(LoggerInterface::class)){
            Di::getContainer()->set(LoggerInterface::class,new static());
        }

        $logger = Di::getContainer()->get(LoggerInterface::class);
        return call_user_func_array([$logger, $name], $arguments);
    }

    public function log($level, $message, array $context = array())
    {
        $levels = [
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::DEBUG,
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING
        ];

        if (!in_array($level, $levels)) {
            throw new InvalidArgumentException('LogLevel does not exist');
        }
        // 构建一个花括号包含的键名的替换数组
        $replace = array();
        foreach ($context as $key => $val) {
            // 检查该值是否可以转换为字符串
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // 替换记录信息中的占位符，最后返回修改后的记录信息。
        $logger = strtr($message, $replace);
        $this->file_put_contents($level, $logger);
    }

    /**
     * @param $level
     * @param $data
     */
    protected function file_put_contents($level, $data): void
    {
        $log_dir = $this->log_dir . '/' . date('Ym');
        $filename =  date('d') . "_{$level}.log";
        $time = date(DATE_ATOM);

        if (!is_dir($log_dir)){
            mkdir($log_dir, 755, true);
        }

        file_put_contents("{$log_dir}/{$filename}", "[ {$level} ] [{$time}] \r\n{$data} \r\n\r\n", FILE_APPEND);
    }
}