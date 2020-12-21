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

        $channel = Db::name('package');
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
}
