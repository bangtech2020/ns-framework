<?php


namespace app\bangtech\app_upgrade\extend\manage;


use helper\Db;
use think\db\Query;

class Package extends Base
{
    public function getList()
    {
        $page = $this->request->getGet()->get('page', 1);
        $limit = $this->request->getGet()->get('limit', 30);
        $search = $this->request->getGet()->get('search', '{}');
        $search = json_decode($search, true);
        if (empty($search)) $search = [];

        //Di::getContainer()->get(OutputInterface::class)->dump($search);

        $package = Db::name('package');
        $package->join('apps', 'apps.id = package.app_id');
        $package->join('users create_users', 'create_users.id = package.create_user_id', 'LEFT');
        $package->join('users update_users', 'update_users.id = package.update_user_id', 'LEFT');
        $package->field("apps.app_name,apps.app_describe,package.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $package->where(function (Query $query) use ($search) {
            $this->whereObj($query, $search);
        });
        //Di::getContainer()->get(OutputInterface::class)->writeln($apps->buildSql());
        $ret = $package->page($page, $limit)->select();

        if ($ret === false){
            $this->result(null,1,'查询失败');
        }

        $this->result($ret,0,'查询成功');
    }

    public function getInfo()
    {
        $package_id = $this->request->getGet()->get('package_id', 0);
        $package_id = intval($package_id);
        $package_name = $this->request->getGet()->get('package_name', '');
        $search = $this->request->getGet()->get('search', '{}');
        $search = json_decode($search, true);
        if (empty($search)) $search = [];

        if (!$package_id && !$package_name) {
            $this->result(null, 1, 'channel_id和package_name 必传一个');
        }

        $package = Db::name('package');
        $package->join('apps', 'apps.id = package.app_id');
        $package->join('users create_users', 'create_users.id = package.create_user_id', 'LEFT');
        $package->join('users update_users', 'update_users.id = package.update_user_id', 'LEFT');
        $package->field("apps.app_name,apps.app_describe,package.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        if ($package_id){
            $package->where('package.id',$package_id);
        }
        if ($package_name){
            $package->where('package.name',$package_name);
        }
        $package->where(function (Query $query) use ($search) {
            $this->whereObj($query, $search);
        });
        //Di::getContainer()->get(OutputInterface::class)->writeln($apps->buildSql());
        $ret = $package->find();

        if ($ret === false){
            $this->result(null,1,'查询失败');
        }

        $this->result($ret,0,'查询成功');
    }

    /**
     * 添加
     */
    public function add()
    {
        $app_id = $this->request->getPost()->get('app_id', 0);
        $name = $this->request->getPost()->get('name', '');
        $type = $this->request->getPost()->get('type', '');

        if (!$app_id) $this->result(null, 1, 'app_id 必传');
        if (!$name) $this->result(null, 1, 'name 必传');
        if (!$type) $this->result(null, 1, 'type 必传');

        $_db = [
            'app_id' => $app_id,
            'name' => $name,
            'type' => $type,
            'create_user' => ($this->getUser())['id'],
            'create_time' => date("Y-m-d H:i:s", time()),
        ];

        $ret = Db::name('package')->insert($_db);
        if ($ret === false) {
            $this->result(null, 1, '添加失败');
        }

        $this->result(null, 0, '添加成功');
    }

    /**
     * 更新
     */
    public function update()
    {
        $package_id = $this->request->getGet()->get('package_id', 0);
        $app_id = $this->request->getPost()->get('app_id', 0);
        $name = $this->request->getPost()->get('name', '');
        $type = $this->request->getPost()->get('type', '');

        if (!$package_id) {
            $this->result(null, 1, 'package_id 必传');
        }

        $_db = [
            'update_user_id' => ($this->getUser())['id'],
            'update_time' => date("Y-m-d H:i:s", time()),
        ];

        if ($app_id) $_db['app_id'] = $app_id;
        if ($name) $_db['name'] = $name;
        if ($type) $_db['type'] = $type;

        if (count($_db) == 2) {
            $this->result(null, 1, '无信息改动');
        }

        $ret = Db::name('package')->where('id', $package_id)->update($_db);
        if ($ret === false) {
            $this->result(null, 1, '修改失败');
        }

        $this->result(null, 0, '修改成功');
    }

    /**
     * 删除
     */
    public function delete()
    {
        $package_id = $this->request->getGet()->get('package_id', 0);
        if (!$package_id) {
            $this->result(null, 1, 'package_id 必传');
        }

        $ret = Db::name('package')->where('id', $package_id)->delete();
        if (!$ret) {
            $this->result(null, 1, '删除失败');
        }
        $this->result(null, 1, '删除成功');
    }
}
