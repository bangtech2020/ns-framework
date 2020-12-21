<?php
declare(strict_types=1);

namespace app\bangtech\app_upgrade\extend;


use helper\Db;
use helper\Di;
use interfaces\Console\OutputInterface;
use think\db\Query;

class Index extends \helper\Internet\Controller
{
    public function index()
    {

    }

    public function checkVersion()
    {
        $app_id       = $this->request->getGet()->get('app_id','');
        $channel_code = $this->request->getGet()->get('channel_code','');
        $version_code = $this->request->getGet()->get('version_code','');

        if (!$channel_code){
            $this->result(null,1,'渠道代码必传');
        }

        if (!$app_id){
            $this->result(null,1,'APPID必传');
        }

        if (!$version_code){
            $this->result(null,1,'版本号必传');
        }




        $package = Db::name('package');
        $package->join('channel', 'channel.id = package.channel_id');
        $package->join('versions', 'versions.id = package.version_id');
        $package->field([
            'package.id',
            'versions.id' => 'version_id',
            'versions.version_code' => 'version_code',
            'versions.min_version' => 'min_version',
            'package.download_url',
        ]);
        $package->where('package.status', '<>', 0);
        //未删除
        $package->where('versions.is_delete', '=', 0);
        $package->where('channel.code', $channel_code);
        $package->where('channel.app_id', $app_id);

        // Di::getContainer()->get(OutputInterface::class)->writeln($package->buildSql());
        $ret = $package->select();

        if ($ret === false) {
            $this->result(null, 1, '查询失败');
        }

        $max_version = $version_code;

        foreach ($ret as $key => $value) {
            if (version_compare($version_code,$value['min_version'],'>') && version_compare($max_version,$value['version_code'],'<')){
                $max_version = $value['version_code'];
            }
        }

        $index = array_search($max_version,array_column($ret,'version_code'));

        $ret = $ret[$index];

        if ($index === false || version_compare($max_version,$version_code,'=')){
            $this->result(null, 1, '无需升级');
        }

        $versions = Db::name('versions')
            ->where('id',$ret['version_id'])
            ->where('is_delete','=',0)
            ->field(['update_type', 'description',])
            ->find();

        if (!$versions){
            $this->result(null,1,'检查失败');
        }
        $ret['update_type'] = $versions['update_type'];
        $ret['description'] = $versions['description'];

        $this->result($ret, 0, '查询成功');
    }
}
