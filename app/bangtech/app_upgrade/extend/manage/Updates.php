<?php


namespace app\bangtech\app_upgrade\extend\manage;

use helper\Db;
use helper\Di;
use interfaces\Console\OutputInterface;
use think\db\Query;

/**
 * 包管理
 * Class Updates
 * @package app\bangtech\app_upgrade\extend\manage
 */
class Updates extends Base
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


        $updates = Db::name('updates');
        $updates->join('channel', 'channel.id = updates.channel_id');
        $updates->join('versions', 'versions.id = updates.version_id');
        $updates->join('users create_users', 'create_users.id = updates.create_user_id', 'LEFT');
        $updates->join('users update_users', 'update_users.id = updates.update_user_id', 'LEFT');
        $updates->field([
            'channel.name' => 'channel_name',
            'channel.code' => 'channel_code',
            'versions.version_code' => 'version_code',
            'versions.update_type' => 'version_update_type',
            'updates.*',
            'create_users.nickname' => 'create_users_nickname',
            'update_users.nickname' => 'update_users_nickname',
        ]);
        $updates->where('updates.status', '<>', 0);
        $updates->where(function (Query $query) use ($search) {
            $this->whereObj($query, $search);
        });
        Di::getContainer()->get(OutputInterface::class)->writeln($updates->page($page, $limit)->buildSql());
        $ret = $updates->page($page, $limit)->select();

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
        $update_id = $this->request->getGet()->get('update_id', 0);
        $search = $this->request->getGet()->get('search', '{}');
        $search = json_decode($search, true);
        if (empty($search)) $search = [];

        if (!$update_id) {
            $this->result(null, 1, 'update_id 必传');
        }

        $updates = Db::name('updates');
        $updates->join('channel', 'channel.id = updates.channel_id');
        $updates->join('versions', 'versions.id = updates.version_id');
        $updates->join('users create_users', 'create_users.id = updates.create_user_id', 'LEFT');
        $updates->join('users update_users', 'update_users.id = updates.update_user_id', 'LEFT');
        $updates->field([
            'channel.name' => 'channel_name',
            'channel.code' => 'channel_code',
            'versions.version_code' => 'version_code',
            'versions.update_type' => 'version_update_type',
            'updates.*',
            'create_users.nickname' => 'create_users_nickname',
            'update_users.nickname' => 'update_users_nickname',
        ]);
        $updates->where('updates.id', $update_id);
        $updates->where('updates.status', '<>', 0);
        $updates->where(function (Query $query) use ($search) {
            $this->whereObj($query, $search);
        });
        $ret = $updates->find();

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

        $ret = Db::name('updates')->insertAll($_db);
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
        $update_id = $this->request->getGet()->get('update_id', 0);
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


        $ret = Db::name('updates')->where('id', $update_id)->update($_db);

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
        $update_id = $this->request->getGet()->get('update_id', 0);
        if (!$update_id) {
            $this->result(null, 1, 'update_id 必传');
        }

        $_db = [
            'status' => 0,
            'update_user_id' => ($this->getUser())['id'],
            'update_time' => date("Y-m-d H:i:s", time()),
        ];

        $ret = Db::name('updates')->where('id', $update_id)->update($_db);
        if (!$ret) {
            $this->result(null, 1, '删除失败');
        }
        $this->result(null, 1, '删除成功');
    }
}
