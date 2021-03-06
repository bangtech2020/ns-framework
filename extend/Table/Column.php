<?php


namespace extend\Table;


//未完成，目标变成Table类的传参
class Column
{
    protected $hasColumn = [];
    protected $table = [
        'column' => [] ,
        'data'   => []
    ];

    /**
     * 创建列
     * @param string $name
     * @param int $type
     * @return bool
     */
    public function add(string $name, int $type = Table::TYPE_STRING,bool $pk = false)
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
     * 强制刷新
     * @param bool $force
     * @return array
     */
    public function getHasColumn($force = false)
    {
        if (count($this->hasColumn) == 0 || $force == true){
            $this->hasColumn = array_column($this->table['column'], 'name');
        }
        return $this->hasColumn;
    }


    /**
     * @return array
     */
    public function getTable(): array
    {
        return $this->table;
    }
}
