<?php


namespace app\bangtech\app_upgrade\extend\manage;


use bangtech\swooleOrm\db\Query;
use helper\Internet\Controller;

class Base extends Controller
{
    protected function getUser()
    {
        return ['id'=>1,'nickname' => '春哥', 'mobile' => '13000000000'];
    }

    protected function whereObj(Query $query, $search)
    {
        $ops = ['=', '<>', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'BETWEEN', 'NOT BETWEEN', 'IN', 'NOT IN',
            'NULL', 'NOT NULL', 'EXISTS', 'NOT EXISTS', '> TIME	', '< TIME', '>= TIME', '<= TIME'];
        foreach ($search as $key => $value) {
            //过滤
            if (is_string($key)) $key = addslashes($key);
            if (is_array($value)) {
                foreach ($value as $item) {
                    //参数过滤
                    if (is_array($item['value'])) continue;
                    if (is_string($item['value'])) $item['value'] = addslashes($item['value']);
                    if (!isset($item['op']) || empty($item['op'])) $item['op'] = '=';

                    $item['op'] = strtoupper($item['op']);
                    $item['where'] = strtoupper($item['where']);

                    //高级筛选
                    if (in_array($item['op'], $ops)) {
                        if (isset($item['where']) && $item['where'] == 'OR') {
                            $query->whereOr($key, $item['op'], $item['value']);
                        } else {
                            $query->where($key, $item['op'], $item['value']);
                        }
                    }
                }
            } else {
                if (is_array($value)) continue;
                if (is_string($value)) $value = addslashes($value);
                $query->where(addslashes($key), $value);
            }
        }
    }
}
