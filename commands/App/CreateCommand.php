<?php


namespace commands\App;

use helper\Console\Command;
use StringTemplate\Engine;

class CreateCommand extends Command
{

    protected static $name = 'app:create';
    protected static $description = 'Create an application';

    /**
     * @inheritDoc
     */
    protected function execute($input, $output)
    {
        $replace = [];
        $config = [];

        //获得应用名称
        $author = $this->read('Enter the application author: ');

        while (!$author){
            $author = $this->read('Please!!! Enter the application author: ');
        }

        if (!is_dir(ROOT_PATH."/app/{$author}")){
            mkdir(ROOT_PATH."/app/{$author}",0777,true);
        }

        $replace['author'] = $author;
        $config['author'] = $author;

        //获得应用名称
        $app_id = $this->read('Enter the application ID: ');

        while (!$app_id){
            $app_id = $this->read('Please!!! Enter the application ID: ');
        }

        $replace['app_id'] = $app_id;
        $config['identification'] = $app_id;
        $config['name'] = "{$author}/{$app_id}";
        $config = [
            'name'=>"{$author}/{$app_id}",
            'author'=>$author,
            'identification'=>$app_id,
            'version'=>'0.0.1',
            'description'=>'',
            'copyright'=>'',
            'event'=>[],
            'extend'=>[],
            'command'=>[],
        ];

        if (is_dir(ROOT_PATH."/app/{$author}/{$app_id}")){
            $has_force  = $this->confirm('Whether to force an overwrite to create an application? ',false);

            if (!$has_force) return;

            //强制重写
            delectFileAll(ROOT_PATH."/app/{$author}/{$app_id}");
        }



        $has_extension  = $this->confirm('Is there an extension? ');
        $has_event     = $this->confirm('Is there a event listener? ');
        $has_command    = $this->confirm('Is there a command? ');

        $this->writeln("Start creating the relevant files");
        if ($has_extension){
            $this->createFile(ROOT_PATH."/app/{$author}/{$app_id}/extend",'Index',BASE_PATH.'/commands/App/template/extend/Index.php.tph',$replace);
            $config['extend'][] = ['mode'=> "GET", 'route'=>'','handler'=>'extend\Index@index'];
        }

        if ($has_command){
            $this->createFile(ROOT_PATH."/app/{$author}/{$app_id}/command",'DemoCommand',BASE_PATH.'/commands/App/template/command/DemoCommand.php.tph',$replace);
            $config['command'][] = ['class'=> "command\DemoCommand", 'mode'=>'HAS_COMMAND'];
        }

        file_put_contents(ROOT_PATH."/app/{$author}/{$app_id}/app.json",json_encode($config,JSON_UNESCAPED_SLASHES));

    }


    private function createFile($path, $file_name, $template_file, array $params)
    {
        $params['file_name'] = $file_name;
        $tmp_file = file_get_contents($template_file);

        if (!is_dir($path)){

            $this->output->colored("File does not exist: [{$path}]",'warning');
            $this->output->colored("Start to create: [{$path}]",'info');
            mkdir($path,0777,true);
        }

        $engine = new Engine('${{','}}');
        $tmp_file = $engine->render($tmp_file,$params);

        return file_put_contents("{$path}/{$file_name}.php",$tmp_file);
    }
}
