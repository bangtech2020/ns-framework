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
class Channel extends Controller
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
                        if (!isset($item['op']) || empty($item['op']))  $item['op'] = '=';

                        $item['op'] = strtoupper($item['op']);
                        $item['where'] = strtoupper($item['where']);

                        //高级筛选
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
