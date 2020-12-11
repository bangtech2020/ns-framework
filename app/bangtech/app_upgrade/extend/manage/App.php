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
            foreach ($search as $key => $value) {
                if ($value){
                    $query->where(addslashes($key), addslashes($value));
                }
            }
        })->page($page,$limit)->select();

        $this->result($ret,0,'ok');
        var_dump('hello');
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
