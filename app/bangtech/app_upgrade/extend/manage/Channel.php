<?php


namespace app\bangtech\app_upgrade\extend\manage;


use helper\Db;
use helper\Internet\Controller;
use think\db\Query;

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
        $page = $this->request->getGet()->get('page',1);
        $limit = $this->request->getGet()->get('limit',30);
        $search = $this->request->getGet()->get('search','{}');
        $search = json_decode($search,true);
        if (empty($search)) $search = [];

        //Di::getContainer()->get(OutputInterface::class)->dump($search);

        $channel = Db::name('channel');
        $channel->join('apps','apps.id = channel.app_id');
        $channel->join('users create_users','create_users.id = channel.create_user_id','LEFT');
        $channel->join('users update_users','update_users.id = channel.update_user_id','LEFT');
        $channel->field("apps.app_name,apps.app_describe,channel.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $channel->where(function (Query $query) use ($search) {
            $this->whereObj($query,$search);
        });
        //Di::getContainer()->get(OutputInterface::class)->writeln($apps->buildSql());
        $ret = $channel->page($page,$limit)->select();

        $this->result($ret,0,'ok');
    }

    /**
     * 获取信息
     */
    public function getInfo()
    {
        $channel_id = $this->request->getGet()->get('channel_id',0);
        $search = $this->request->getGet()->get('search','{}');
        $search = json_decode($search,true);
        if (empty($search)) $search = [];
        if (!$channel_id){
            $this->result(null,1,'channel_id 必传');
        }

        $channel = Db::name('channel');
        $channel->where('channel.id',$channel_id);
        $channel->join('apps','apps.id = channel.app_id');
        $channel->join('users create_users','create_users.id = channel.create_user_id','LEFT');
        $channel->join('users update_users','update_users.id = channel.update_user_id','LEFT');
        $channel->field("apps.app_name,apps.app_describe,channel.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $channel->where(function (Query $query) use ($search) {
            $this->whereObj($query,$search);
        });
        $ret = $channel->find();

        $this->result($ret,0,'ok');
    }

    /**
     * 添加
     */
    public function add()
    {

    }

    /**
     * 更新
     */
    public function update()
    {

    }

    /**
     * 删除
     */
    public function delete()
    {

    }
}
