<?php


namespace app\bangtech\app_upgrade\extend\manage;


use bangtech\swooleOrm\db\Query;
use helper\Db;
use helper\Internet\Controller;

/**
 * 渠道
 * Class Channel
 * @package app\bangtech\app_upgrade\extend\manage
 */
class Channel extends Base
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

        //Di::getContainer()->get(OutputInterface::class)->dump($search);

        $channel = Db::name('channel');
        $channel->join('apps', 'apps.id = channel.app_id');
        $channel->join('users create_users', 'create_users.id = channel.create_user_id', 'LEFT');
        $channel->join('users update_users', 'update_users.id = channel.update_user_id', 'LEFT');
        $channel->field("apps.app_name,apps.app_describe,channel.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $channel->where(function (Query $query) use ($search) {
            $this->whereObj($query, $search);
        });
        //Di::getContainer()->get(OutputInterface::class)->writeln($apps->buildSql());
        $ret = $channel->page($page, $limit)->select();

        if ($ret === false){
            $this->result(null,1,'查询失败');
        }

        $this->result($ret,0,'查询成功');
    }

    /**
     * 获取信息
     */
    public function getInfo()
    {
        $channel_id = $this->request->getGet()->get('channel_id', 0);
        $search = $this->request->getGet()->get('search', '{}');
        $search = json_decode($search, true);
        if (empty($search)) $search = [];
        if (!$channel_id) {
            $this->result(null, 1, 'channel_id 必传');
        }

        $channel = Db::name('channel');
        $channel->where('channel.id', $channel_id);
        $channel->join('apps', 'apps.id = channel.app_id');
        $channel->join('users create_users', 'create_users.id = channel.create_user_id', 'LEFT');
        $channel->join('users update_users', 'update_users.id = channel.update_user_id', 'LEFT');
        $channel->field("apps.app_name,apps.app_describe,channel.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $channel->where(function (Query $query) use ($search) {
            $this->whereObj($query, $search);
        });
        $ret = $channel->find();

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
        $code = $this->request->getPost()->get('code', '');

        if (!$app_id) $this->result(null, 1, 'app_id 必传');
        if (!$name) $this->result(null, 1, 'name 必传');
        if (!$code) $this->result(null, 1, 'code 必传');

        $_db = [
            'app_id' => $app_id,
            'name' => $name,
            'code' => $code,
            'create_user_id' => ($this->getUser())['id'],
            'create_time' => date("Y-m-d H:i:s", time()),
            'status' => 1
        ];

        $ret = Db::name('channel')->insert($_db);
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
        $channel_id = $this->request->getPost()->get('channel_id', 0);
        $app_id = $this->request->getPost()->get('app_id', 0);
        $name = $this->request->getPost()->get('name', '');
        $code = $this->request->getPost()->get('code', '');

        $_db = [
            'update_user_id' => ($this->getUser())['id'],
            'update_time' => date("Y-m-d H:i:s", time()),
        ];

        if ($app_id) $_db['app_id'] = $app_id;
        if ($name) $_db['name'] = $name;
        if ($code) $_db['code'] = $code;

        if (count($_db) == 2) {
            $this->result(null, 1, '无信息改动');
        }

        $ret = Db::name('channel')->where('id', $channel_id)->update($_db);
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
        $channel_id = $this->request->getPost()->get('channel_id', 0);
        if (!$channel_id) {
            $this->result(null, 1, 'channel_id 必传');
        }

        $_db = [
            'status' => 0,
            'update_user_id' => ($this->getUser())['id'],
            'update_time' => date("Y-m-d H:i:s", time()),
        ];

        $ret = Db::name('apps')->where('id', $channel_id)->update($_db);
        if (!$ret) {
            $this->result(null, 1, '删除失败');
        }
        $this->result(null, 1, '删除成功');
    }
}
