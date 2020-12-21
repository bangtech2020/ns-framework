<?php


namespace app\bangtech\app_upgrade\extend\manage;


use helper\Db;
use helper\Di;
use interfaces\Console\OutputInterface;
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

        $versions = Db::name('versions');
        $versions->where('is_delete',0);
        $versions->join('users create_users','create_users.id = versions.create_user_id','LEFT');
        $versions->join('users update_users','update_users.id = versions.update_user_id','LEFT');
        $versions->field("versions.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $versions->where(function (Query $query) use ($search) {
            $this->whereObj($query,$search);
        });
        // Di::getContainer()->get(OutputInterface::class)->writeln($versions->page($page,$limit)->buildSql());
        $ret = $versions->page($page,$limit)->select();

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

        $versions = Db::name('versions');
        $versions->where('is_delete',0);
        $versions->join('users create_users','create_users.id = versions.create_user_id','LEFT');
        $versions->join('users update_users','update_users.id = versions.update_user_id','LEFT');
        $versions->field("versions.*,create_users.nickname as create_users_nickname,update_users.nickname as update_users_nickname");
        $versions->where(function (Query $query) use ($search) {
            $this->whereObj($query,$search);
        });
        $ret = $versions->find();

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

        if (!$version_id) {
            $this->result(null, 1, 'version_id 必传');
        }

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
