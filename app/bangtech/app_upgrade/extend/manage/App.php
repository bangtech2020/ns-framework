<?php


namespace app\bangtech\app_upgrade\extend\manage;


use helper\Db;
use helper\Internet\Controller;
use think\db\Query;

/**
 * 应用管理
 * Class App
 * @package app\bangtech\app_upgrade\extend\manage
 */
class App extends Controller
{
    /**
     * 获取应用列表
     */
    public function getList()
    {
        $page = $this->request->getGet()->get('page',1);
        $limit = $this->request->getGet()->get('limit',30);
        $search = $this->request->getGet()->get('search','{}');
        $search = json_decode($search,true);

        $ret = Db::name('apps')->where(function (Query $query) use ($search) {
            $ops = ['=', '<>', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'BETWEEN', 'NOT BETWEEN', 'IN', 'NOT IN',
                'NULL', 'NOT NULL', 'EXISTS', 'NOT EXISTS', '> TIME	', '< TIME', '>= TIME', '<= TIME'];
            foreach ($search as $key => $value) {
                //过滤
                if (is_string($key)) $key = addslashes($key);
                if (is_array($value)){
                    foreach ($value as $item) {
                        //参数过滤
                        if (is_array($item['value'])) continue;
                        if (is_string($item['value'])) $item['value'] = addslashes($item['value']);

                        $item['op'] = strtoupper($item['op']);
                        $item['where'] = strtoupper($item['where']);

                        //高级筛选
                        if (!isset($item['op']) || empty($item['op'])){
                            $item['op'] = '=';
                        }
                        if (in_array($item['op'],$ops)){
                            if (isset($item['where']) && $item['where'] == 'OR'){
                                $query->whereOr($key, $item['op'], $item['value']);
                            }else{
                                $query->where($key, $item['op'], $item['value']);
                            }
                        }
                    }
                }else{
                    if (is_array($value)) continue;
                    if (is_string($value)) $value = addslashes($value);
                    $query->where(addslashes($key), $value);
                }
            }
        })->page($page,$limit)->select();

        $this->result($ret,0,'ok');
    }

    /**
     * 获取应用信息
     */
    public function getInfo()
    {
        $appid = $this->request->getGet()->get('appid',null);

        if (empty($appid) || $appid == null){
            $this->result([],1,'appid必传');
        }

        $app_info = Db::name('apps')->where('id',$appid)->find();
        $this->result($app_info,0,'ok');
    }

    /**
     * 添加应用
     */
    public function add()
    {

    }

    /**
     * 更新应用
     */
    public function update()
    {

    }

    /**
     * 删除应用
     */
    public function delete()
    {

    }
}
