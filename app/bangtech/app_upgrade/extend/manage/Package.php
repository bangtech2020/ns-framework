<?php


namespace app\bangtech\app_upgrade\extend\manage;

use helper\Db;
use helper\Di;
use interfaces\Console\OutputInterface;
use think\db\Query;

/**
 * 包管理
 * Class Package
 * @package app\bangtech\app_upgrade\extend\manage
 */
class Package extends Base
{
    /**
     * 获取列表
     */
    public function getList()
    {
        $page = $this->request->getGet()->get('page', 1);
        $limit = $this->request->getGet()->get('limit', 30);
        $search = $this->request->getGet()->get('search', '{}');
        $search = json_decode($search, true);
        if (empty($search)) $search = [];


        $package = Db::name('package');
        $package->join('channel', 'channel.id = package.channel_id');
        $package->join('versions', 'versions.id = package.version_id');
        $package->join('users create_users', 'create_users.id = package.create_user_id', 'LEFT');
        $package->join('users update_users', 'update_users.id = package.update_user_id', 'LEFT');
        $package->field([
            'channel.name' => 'channel_name',
            'channel.code' => 'channel_code',
            'versions.version_code' => 'version_code',
            'versions.update_type' => 'version_update_type',
            'package.*',
            'create_users.nickname' => 'create_users_nickname',
            'update_users.nickname' => 'update_users_nickname',
        ]);
        $package->where('package.status', '<>', 0);
        $package->where(function (Query $query) use ($search) {
            $this->whereObj($query, $search);
        });
        Di::getContainer()->get(OutputInterface::class)->writeln($package->page($page, $limit)->buildSql());
        $ret = $package->page($page, $limit)->select();

        if ($ret === false) {
            $this->result(null, 1, '查询失败');
        }

        $this->result($ret, 0, '查询成功');
    }

    /**
     * 获取信息
     */
    public function getInfo()
    {
        $package_id = $this->request->getGet()->get('package_id', 0);
        $search = $this->request->getGet()->get('search', '{}');
        $search = json_decode($search, true);
        if (empty($search)) $search = [];

        $package = Db::name('package');
        $package->join('channel', 'channel.id = package.channel_id');
        $package->join('versions', 'versions.id = package.version_id');
        $package->join('users create_users', 'create_users.id = package.create_user_id', 'LEFT');
        $package->join('users update_users', 'update_users.id = package.update_user_id', 'LEFT');
        $package->field([
            'channel.name' => 'channel_name',
            'channel.code' => 'channel_code',
            'versions.version_code' => 'version_code',
            'versions.update_type' => 'version_update_type',
            'package.*',
            'create_users.nickname' => 'create_users_nickname',
            'update_users.nickname' => 'update_users_nickname',
        ]);
        $package->where('id', $package_id);
        $package->where('status', '<>', 0);
        $package->where(function (Query $query) use ($search) {
            $this->whereObj($query, $search);
        });
        $ret = $package->find();

        if ($ret === false) {
            $this->result(null, 1, '查询失败');
        }

        $this->result($ret, 0, '查询成功');
    }

    /**
     * 添加
     */
    public function add()
    {
        $channel_ids = $this->request->getPost()->get('channel_ids', '');
        $version_id = $this->request->getPost()->get('version_id', 0);
        $download_url = $this->request->getPost()->get('download_url', 0);


        $channel_ids = explode(',', $channel_ids);

        $_db = [];
        foreach ($channel_ids as $key => $channel_id) {
            if (intval($channel_id) == 0) {
                continue;
            }
            $_db[] = [
                'channel_id' => $channel_id,
                'version_id' => $version_id,
                'download_url' => $download_url,
            ];
        }

        if (count($channel_ids) == 0) {
            $this->result(null, 1, '通道必传');
        }

        $ret = Db::name('package')->insertAll($_db);
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
        $channel_id = $this->request->getPost()->get('channel_id', 0);
        $version_id = $this->request->getPost()->get('version_id', 0);
        $download_url = $this->request->getPost()->get('download_url', 0);

        $_db = [
            'update_user_id' => ($this->getUser())['id'],
            'update_time' => date("Y-m-d H:i:s", time()),
        ];


        if ($channel_id) $_db['channel_id'] = $channel_id;
        if ($version_id) $_db['version_id'] = $version_id;
        if ($download_url) $_db['download_url'] = $download_url;

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

        $_db = [
            'status' => 0,
            'update_user_id' => ($this->getUser())['id'],
            'update_time' => date("Y-m-d H:i:s", time()),
        ];

        $ret = Db::name('package')->where('id', $package_id)->update($_db);
        if (!$ret) {
            $this->result(null, 1, '删除失败');
        }
        $this->result(null, 1, '删除成功');
    }
}
