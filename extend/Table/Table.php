<?php

namespace extend\Table;

/**
 * Class Table
 * @package extend\Table
 */
class Table
{
    /**
     *
     */
    const TYPE_INT    = 1;
    /**
     *
     */
    const TYPE_FLOAT  = 2;
    /**
     *
     */
    const TYPE_STRING = 3;

    /**
     * object
     */
    const TYPE_OBJECT = 4;


    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var array
     */
    protected $indexes = [];
    /**
     * @var array
     */
    protected $hasColumn = [];

    /**
     * @var array
     */
    protected $table = [
        'column' => [] ,
        'data'   => []
    ];


    /**
     * @return array
     */
    public function getTable()
    {
        return $this->table;
    }


    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->table['data'];
    }


    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->table['column'];
    }


    /**
     * @return int
     */
    public function agetIndex()
    {
        return $this->index;
    }

    /**
     * @return array
     */
    public function getIndexs()
    {
        return $this->indexes;
    }





    /**
     * 创建列
     * @param string $name
     * @param int $type
     * @return bool
     */
    public function column(string $name, int $type = Table::TYPE_STRING,bool $pk = false)
    {
        //防止name被重复写
        foreach ($this->table['column'] as $key => $value) {
            if ($value['name'] == $name){
                return false;
            }
        }

        $this->table['column'][] = [
            'name' => $name,//名称
            'type' => $type,//类型
            'pk'   => $pk //主键
        ];
        $this->getHasColumn(true);
        return true;
    }

    /**
     * @param array $value
     */
    public function insert(array $values)
    {
        $data = [];
        //获取数据才内存数组的下标
        $index = $this->getIndex();
        foreach ($values as $name => $value) {
            //获取定义的列数据
            $column_index = array_search($name, $this->getHasColumn());
            $column = $this->table['column'][$column_index];
            //根据定义列过滤实际值
            $value = $this->valueFiltration($value, $column['type']);
            $data[$column['name']] = $value;
            //设置索引
            $this->setIndexs($index, $value, $column);
        }
        //将数据写入内存变量
        $this->table['data'][$index] = $data;
    }


    /**
     * 数据删除
     * @param string $name
     * @param $value
     * @return bool
     */
    public function delect(string $name, $value){
        $this->delete($name,$value);
    }

    /**
     * 数据删除
     * @param string $name
     * @param $value
     * @return bool
     */
    public function delete(string $name, $value)
    {
        if (!isset($this->indexes[$name][$value])){
            return true;
        }

        $find_datas = [];
        //数据删除应该分三步，找到需要删除的数据 删除索引 删除数据

        //如果是数组 则需要删除一批对应数据
        if (is_array($this->indexes[$name][$value])){
            $find_datas = $this->indexes[$name][$value];
        }else{
            //非数组则表示为该数据为主键
            $find_datas = [$this->indexes[$name][$value]];
        }

        //删除索引
        foreach ($find_datas as $i => $find_data) {
            foreach ($this->indexes as $field => $indexes) {
                //判断是否是一维数组，一维数组表示该字段属于唯一键
                if (count($indexes)==count($indexes, 1)) {
                    $s_k = array_search($find_data,$indexes);
                    if ($s_k !== false){
                        unset($this->indexes[$field][$s_k]);
                    }
                }else{
                    //多维数组需要找到对应字段搜索索引
                    $s_k = array_search($find_data,$indexes[$this->table['data'][$find_data][$field]]);
                    if ($s_k !== false){
                        unset($this->indexes[$field][$this->table['data'][$find_data][$field]][$s_k]);
                    }
                }

            }
        }

        //删除数据
        foreach ($find_datas as $i => $find_data) {
            unset($this->table['data'][$find_data]);
        }

        unset($find_datas);
        return true;
    }


    /**
     *
     */
    public function update()
    {

    }

    /**
     * @param string $name
     * @param $value
     * @param string $field
     * @return mixed
     */
    public function find(string $name, $value, string $field = '*')
    {
        if (!isset($this->indexes[$name][$value])){
            return false;
        }
        if (is_array($this->indexes[$name][$value])){
            return $this->table['data'][$this->indexes[$name][$value][array_key_first($this->indexes[$name][$value])]];
        }else{
            return $this->table['data'][$this->indexes[$name][$value]];
        }
    }


    /**
     * @param string $name
     * @param $value
     * @param string $field
     * @param int|null $limit
     * @return array
     */
    public function select(string $name, $value, string $field = '*', int $limit = null)
    {
        //取出字段是否是唯一索引
        $index = array_search($name, $this->getHasColumn());
        $pk = $this->table['column'][$index]['pk'];

        $data = [];

        //检查需要获取的数据是否超出范围，是否合法
        if (!isset($this->indexes[$name][$value]) || empty($this->indexes[$name][$value])){
            return [];
        }elseif (!is_object($this->indexes[$name][$value]) && !is_array($this->indexes[$name][$value])){
            $data[0] = $this->table['data'][$this->indexes[$name][$value]];
            return $data;
        } elseif (empty($limit) || count($this->indexes[$name][$value]) < $limit){
            $limit = count($this->indexes[$name][$value]);
        }

        //唯一索引数据只有一条，直接返回
        if ($pk){
            $data[0] = $this->table['data'][$this->indexes[$name][$value]];
            return $data;
        }

        //剔除无用索引
        foreach ($this->indexes[$name][$value] as $index => $str) {
            if (!isset($this->table['data'][$this->indexes[$name][$value][$index]])){
                unset($this->indexes[$name][$value][$index]);
            }
        }
        $this->indexes[$name][$value] = array_values($this->indexes[$name][$value]);

        //不是唯一索引需要将需要的条数返回
        $i = 0;
        foreach ($this->indexes[$name][$value] as $index => $str) {
            //采用计数器方式返回数据
            if ($i >= $limit){
                return $data;
            }
            $data[] = $this->table['data'][$this->indexes[$name][$value][$i]];
            $i++;
        }
        return $data;
    }

    /**
     * @return int
     */
    protected function getIndex()
    {
        return $this->index++;
    }

    /**
     * @param $value
     * @param int $type
     * @return float|int|string
     */
    protected function valueFiltration($value, int $type)
    {
        if (is_array($value)){
            foreach ($value as $key => &$item) {
                $item = $this->valueFiltration($item,$type);
            }
            return $value;
        }

        if ($type == Table::TYPE_INT){
            return intval($value);
        }elseif ($type == Table::TYPE_FLOAT){
            return floatval($value);
        }elseif ($type == Table::TYPE_STRING){
            return strval($value);
        }else{
            return $value;
        }
    }

    /**
     * 设置索引
     * @param int $index
     * @param $data
     * @param array $column
     */
    protected function setIndexs(int $index, $data, $column) :void
    {

        if (is_string($data) || is_numeric($data)){
            if ($column['pk'] !== true){
                $this->indexes[$column['name']][$data][] = $index;
            }else{
                $this->indexes[$column['name']][$data] = $index;
            }
        }elseif (is_array($data) && $column['pk'] !== true) {
            foreach ($data as $key => $datum) {
                $this->setIndexs($index, $datum,$column);
            }
        } elseif(is_array($data) && $column['pk'] === true) {
            $this->setIndexs($index,md5(json_encode($data)),$column);
        }
    }



    /**
     * 设置索引
     * @param int $index
     * @param $data
     * @param array $column
     */
    protected function unsetIndexs(int $index, $data, $column) :void
    {

        if (is_string($data) || is_numeric($data)){
            if ($column['pk'] !== true){
                $this->indexes[$column['name']][$data][] = $index;
            }else{
                $this->indexes[$column['name']][$data] = $index;
            }
        }elseif (is_array($data) && $column['pk'] !== true) {
            foreach ($data as $key => $datum) {
                $this->setIndexs($index, $datum,$column);
            }
        } elseif(is_array($data) && $column['pk'] === true) {
            $this->setIndexs($index,md5(json_encode($data)),$column);
        }
    }

    /**
     * 强制刷新
     * @param bool $force
     * @return array
     */
    protected function getHasColumn($force = false)
    {
        if (count($this->hasColumn) == 0 || $force == true){
            $this->hasColumn = array_column($this->table['column'], 'name');
        }
        return $this->hasColumn;
    }
}
