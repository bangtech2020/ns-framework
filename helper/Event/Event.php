<?php


namespace helper\Event;


use helper\Di;
use interfaces\Console\OutputInterface;
use function DI\value;

class Event
{
    /**
     * 事件触发
     * @param $event_name
     * @param $context
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function process($event_name,$context)
    {
        $output = Di::getContainer()->get(OutputInterface::class);
        $listener = Di::getContainer()->get(Listener::class);
        $event_action = $listener->getEventAction($event_name);
        foreach ($event_action as $key => $value) {
            [$class,$action] = explode('@',$value['event_action']);
            try {
                $obj = new $class;
                call_user_func([$obj,$action],$context);
                unset($obj);
            } catch (\Error $exception){
                $output->writeln("事件:{$event_name},调用响应:{$value}失败");
            } catch (\ErrorException $exception){
                $output->writeln("事件:{$event_name},调用响应:{$value}失败");
            } catch (\Exception $exception){
                $output->writeln("事件:{$event_name},调用响应:{$value}失败");
            } catch (\Throwable $exception){
                $output->writeln("事件:{$event_name},调用响应:{$value}失败");
            }
        }
    }
}
