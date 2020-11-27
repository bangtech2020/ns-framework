<?php


namespace helper\Event;


use DI\DependencyException;
use DI\NotFoundException;
use helper\Di;
use interfaces\Console\OutputInterface;

class Event
{
    /**
     * 事件触发
     * @param $event_name
     * @param $context
     */
    public static function process($event_name, $context)
    {
        self::run("{$event_name}_before", $context);
        self::run("{$event_name}", $context);
        self::run("{$event_name}_after", $context);
    }

    private static function run($event_name, $context)
    {

        try {
            $output = Di::getContainer()->get(OutputInterface::class);
            $listener = Di::getContainer()->get(Listener::class);
            $event_action = $listener->getEventAction($event_name);
            foreach ($event_action as $key => $value) {
                if (is_object($value['event_action'])) {
                    $obj = $value['event_action'];
                    $action = 'execute';
                } elseif (is_string($value['event_action'])) {
                    [$class, $action] = explode('@', $value['event_action']);
                    $obj = new $class;
                }

                try {
                    call_user_func([$obj, $action], $context);
                    unset($obj);
                } catch (\Throwable $exception) {
                    $output->writeln("事件:{$event_name},调用响应:{$value}失败");
                }
            }
        } catch (DependencyException $e) {
            var_dump($e->getMessage());
        } catch (NotFoundException $e) {
            var_dump($e->getMessage());
        } catch (\Throwable $exception) {
            $output->writeln("事件:{$event_name},调用响应:{$value}失败");
        }

    }
}
