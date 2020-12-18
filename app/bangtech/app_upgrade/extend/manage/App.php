<?php


namespace app\bangtech\app_upgrade\extend\manage;


use helper\Db;
use helper\Di;
use helper\Internet\Controller;
use interfaces\Console\OutputInterface;
use think\db\Query;

/**
 * 应用管理
 * Class App
 * @package app\bangtech\app_upgrade\extend\manage
 */
class App extends Base
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
        if (empty($search)) $search = [];

        //Di::getContainer()->get(OutputInterface::class)->dump($search);
        $apps = Db::name('apps');
        $apps->join('users create_users','create_users.id = apps.create_user_id','LEFT');
        $apps->join('users update_users','update_users.id = apps.update_user_id','LEFT');
        $apps->field("apps.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $apps->where(function (Query $query) use ($search) {
            $this->whereObj($query,$search);
        });
        //Di::getContainer()->get(OutputInterface::class)->writeln($apps->buildSql());
        $ret = $apps->page($page,$limit)->select();

        $this->result($ret,0,'ok');
    }

    /**
     * 获取应用信息
     */
    public function getInfo()
    {
        $app_id = $this->request->getGet()->get('app_id',0);
        $search = $this->request->getGet()->get('search','{}');
        $search = json_decode($search,true);
        if (empty($search)) $search = [];

        if (!$app_id){
            $this->result(null,1,'app_id 必传');
        }

        //Di::getContainer()->get(OutputInterface::class)->dump($search);
        $apps = Db::name('apps');
        $apps->where('apps.id',$app_id);
        $apps->join('users create_users','create_users.id = apps.create_user_id','LEFT');
        $apps->join('users update_users','update_users.id = apps.update_user_id','LEFT');
        $apps->field("apps.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $apps->where(function (Query $query) use ($search) {
            $this->whereObj($query,$search);
        });

        $app_info = $apps->find();
        $this->result($app_info,0,'ok');
    }

    /**
     * 添加应用
     */
    public function add()
    {
        $app_name       = $this->request->getPost()->get('app_name','');
        $app_describe   = $this->request->getPost()->get('app_describe','');

        $_db = [
            'app_name' => $app_name,
            'app_describe' => $app_describe,
            'create_user_id' => ($this->getUser())['id'],
            'create_time' => date("Y-m-d H:i:s",time()),
        ];

        $ret = Db::name('apps')->insert($_db);
        if ($ret === false){
            $this->result(null,1,'添加失败');
        }

        $this->result(null,0,'添加成功');
    }

    /**
     * 更新应用
     */
    public function update()
    {
        $app_id = $this->request->getPost()->get('app_id',0);
        $app_name       = $this->request->getPost()->get('app_name','');
        $app_describe   = $this->request->getPost()->get('app_describe','');

        $_db = [
            'update_user_id' => ($this->getUser())['id'],
            'update_time' => date("Y-m-d H:i:s",time()),
        ];

        if ($app_name) $_db['app_name'] = $app_name;
        if ($app_describe) $_db['app_describe'] = $app_describe;

        if (count($_db) == 2){
            $this->result(null,1,'无信息改动');
        }

        $ret = Db::name('apps')->where('id',$app_id)->update($_db);
        if ($ret === false){
            $this->result(null,1,'修改失败');
        }

        $this->result(null,0,'修改成功');
    }

    /**
     * 删除应用
     */
    public function delete()
    {
        $app_id = $this->request->getPost()->get('app_id',0);
        if (!$app_id){
            $this->result(null,1,'app_id必传');
        }

        $_db = [
            'is_delete'=>1,
            'update_user_id' => ($this->getUser())['id'],
            'update_time'=> date("Y-m-d H:i:s",time()),
        ];

        $ret = Db::name('apps')->where('id',$app_id)->update($_db);
        if (!$ret){
            $this->result(null,1,'删除失败');
        }
        $this->result(null,1,'删除成功');
    }
}
