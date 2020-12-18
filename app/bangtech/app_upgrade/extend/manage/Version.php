<?php


namespace app\bangtech\app_upgrade\extend\manage;


use helper\Db;
use think\db\Query;

class Version extends Base
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

        $apps = Db::name('versions');
        $apps->where('is_delete',0);
        $apps->join('users create_users','create_users.id = apps.create_user_id','LEFT');
        $apps->join('users update_users','update_users.id = apps.update_user_id','LEFT');
        $apps->field("apps.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $apps->where(function (Query $query) use ($search) {
            $this->whereObj($query,$search);
        });
        $ret = $apps->page($page,$limit)->select();

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

        $version_id = $this->request->getGet()->get('version_id',0);
        $search = $this->request->getGet()->get('search','{}');
        $search = json_decode($search,true);
        if (empty($search)) $search = [];

        if (!$version_id){
            $this->result(null,1,'version_id 必传');
        }

        $apps = Db::name('versions');
        $apps->where('is_delete',0);
        $apps->where('id',$version_id);
        $apps->join('users create_users','create_users.id = apps.create_user_id','LEFT');
        $apps->join('users update_users','update_users.id = apps.update_user_id','LEFT');
        $apps->field("apps.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $apps->where(function (Query $query) use ($search) {
            $this->whereObj($query,$search);
        });
        $ret = $apps->find();

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
        $app_id       = $this->request->getPost()->get('app_id',0);
        $version_code = $this->request->getPost()->get('version_code','');
        $min_version  = $this->request->getPost()->get('min_version','');
        $update_type  = $this->request->getPost()->get('update_type','');
        $description  = $this->request->getPost()->get('description','');

        $_db = [
            'app_id' => $app_id,
            'version_code' => $version_code,
            'min_version' => $min_version,
            'update_type' => $update_type,
            'description' => $description,
            'create_user_id	' => ($this->getUser())['id'],
            'create_time' => date("Y-m-d H:i:s",time()),
            'is_delete' => 0
        ];

        $ret = Db::name('versions')->insert($_db);

        if ($ret === false){
            $this->result(null,1,'添加失败');
        }

        $this->result(null,0,'添加成功');
    }

    /**
     * 更新
     */
    public function update()
    {
        $version_id = $this->request->getGet()->get('version_id',0);
        $app_id       = $this->request->getPost()->get('app_id',0);
        $version_code = $this->request->getPost()->get('version_code','');
        $min_version  = $this->request->getPost()->get('min_version','');
        $update_type  = $this->request->getPost()->get('update_type','');
        $description  = $this->request->getPost()->get('description','');

        $_db = [
            'update_user_id	' => ($this->getUser())['id'],
            'update_time' => date("Y-m-d H:i:s",time())
        ];

        if ($app_id) $_db['app_id'] = $app_id;
        if ($version_code) $_db['version_code'] = $version_code;
        if ($min_version) $_db['min_version'] = $min_version;
        if ($update_type) $_db['update_type'] = $update_type;
        if ($description) $_db['description'] = $description;

        $ret = Db::name('versions')->where('version_id',$version_id)->where('is_delete',0)->update($_db);

        if ($ret === false){
            $this->result(null,1,'修改失败');
        }

        $this->result(null,0,'修改成功');
    }

    /**
     * 删除
     */
    public function delete()
    {
        $version_id = $this->request->getGet()->get('version_id',0);
        if (!$version_id){
            $this->result(null,1,'version_id 必传');
        }

        $_db = [
            'is_delete'=>1,
            'update_user_id' => ($this->getUser())['id'],
            'update_time'=> date("Y-m-d H:i:s",time()),
        ];

        $ret = Db::name('apps')->where('id',$version_id)->where('is_delete',0)->update($_db);
        if (!$ret){
            $this->result(null,1,'删除失败');
        }
        $this->result(null,1,'删除成功');
    }
}
