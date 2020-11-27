<?php


namespace helper\Event;


use extend\Table\Table;
use interfaces\Console\OutputInterface;

class Listener
{
    private $table;

    public function __construct()
    {
        $this->table = new Table();
        $this->table->column('event_name' , Table::TYPE_STRING);
        $this->table->column('event_action',Table::TYPE_OBJECT);
    }


    public function registered($event_name, $event_action)
    {
        /**
         * @var OutputInterface $output
         */
        $this->table->insert(['event_name'=>$event_name,'event_action'=>$event_action]);
    }

    /**
     * @param $event_name
     * @return array
     */
    public function getEventAction($event_name)
    {
        return $this->table->select('event_name',$event_name);
    }
}
