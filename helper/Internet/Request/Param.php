<?php


namespace helper\Internet\Request;


class Param
{

    protected $params = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function getAll()
    {
        return $this->params;
    }

    public function get($name, $default = '', $filters = '')
    {
        if (!array_key_exists($name,$this->params)) return $default;
        return $this->filter($this->params[$name],$filters);
    }

    private function filter($value,$filters = '')
    {
        if (!$filters) return $value;
        if (is_string($filters)) $filters = explode('|',$filters);
        if (!is_array($filters)) return $value;
        if (count($filters) == 0) return $value;

        $filter = array_shift($filters);
        $value = call_user_func([$filter],$value);
        return $this->filter($value,$filters);
    }
}
