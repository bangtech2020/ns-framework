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
        Db::name('apps')->where(function (Query $query)  {})->select();
    }

    /**
     * 获取应用信息
     */
    public function getInfo()
    {

    }

    /**
     * 添加应用
     */
    public function add()
    {
        $this->request->getGet();
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
