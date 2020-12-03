<?php


namespace commands\App;


use helper\Console\Command;
use Inhere\Console\IO\Input;

class LoadCommand extends Command
{

    protected static $name = 'app:load';
    protected static $description = 'Load an application';

    public static function aliases(): array
    {
        return ['update'];
    }

    protected function configure(): void
    {
        $this->createDefinition()
            ->addArgument('path', Input::ARG_OPTIONAL, '{atuho}/{id}', '')
            ->addOption('has_dev', '', Input::OPT_BOOLEAN, '是否是开发模式');
    }

    /**
     * 更新{app}/app.json到ns.json和ns.lock
     * @inheritDoc
     */
    protected function execute($input, $output)
    {
        $app_name = $input->getArgument('path', '');
        $has_dev = $input->getOption('has_dev', false);
        if ($app_name !== '') {
            $app_name = [$app_name];
        } else {
            if (!is_file(ROOT_PATH . "/app/ns.json")) {
                $this->output->error("找不到配置文件ns.json");
                return;
            }
            $ns_config = json_decode(file_get_contents(ROOT_PATH . "/app/ns.json"), true);

            $app_name = array_merge($ns_config['require'], $ns_config['require-dev']);
        }

        foreach ($app_name as $key => $value) {
            $this->loadApp($value, $has_dev);
        }
    }


    private function loadApp($app_name, $has_dev)
    {
        $app_path = ROOT_PATH . "/app/{$app_name}";
        $config_file = "{$app_path}/app.json";

        if (!is_dir($app_path)) {
            $this->output->error('Applications don\'t exist!!!!!!');
        }

        if (!is_file($config_file)) {
            $this->output->error('The app does not have an [app.json] file !!!!!!');
        }

        try {
            $this->output->writeln($config_file);
            $app_config = json_decode(file_get_contents($config_file), true);
        } catch (\ErrorException $exception) {
            $this->output->error("App configuration file failed to read!!!");
            return;
        } catch (\Error $exception) {
            $this->output->error("App configuration file failed to read!!!");
            return;
        } catch (\Exception $exception) {
            $this->output->error("App configuration file failed to read!!!");
            return;
        }


        $ns_config = [
            'require' => [],
            'require-dev' => []
        ];

        if (is_file(ROOT_PATH . "/app/ns.json")) {
            try {
                $ns_config = json_decode(file_get_contents(ROOT_PATH . "/app/ns.json"), true);
            } catch (\ErrorException $exception) {
                $ns_config = ['require' => [], 'require-dev' => []];
            } catch (\Error $exception) {
                $ns_config = ['require' => [], 'require-dev' => []];
            } catch (\Exception $exception) {
                $ns_config = ['require' => [], 'require-dev' => []];
            }
        }

        //清空自生原来的依赖关系
        if (isset($ns_config['require'][$app_name])) {
            unset($ns_config['require'][$app_name]);
        }
        if (isset($ns_config['require-dev'][$app_name])) {
            unset($ns_config['require-dev'][$app_name]);
        }

        if ($has_dev) {
            $ns_config['require-dev'][$app_name] = '*';
        } else {
            $ns_config['require'][$app_name] = '*';
        }

        $_has = file_put_contents(ROOT_PATH . "/app/ns.json", json_encode($ns_config, JSON_UNESCAPED_SLASHES));

        if ($_has) {
            $this->output->success("The ns.json file dependency was updated successfully");
        } else {
            $this->output->error("Dependency update failed for ns.json files");
        }

        if (is_file(ROOT_PATH . "/app/ns.lock")) {
            try {
                $ns_lock = json_decode(file_get_contents(ROOT_PATH . "/app/ns.lock"), true);
            } catch (\ErrorException $exception) {
                $ns_lock = ['packages' => []];
            } catch (\Error $exception) {
                $ns_lock = ['packages' => []];
            } catch (\Exception $exception) {
                $ns_lock = ['packages' => []];
            }
        } else {
            $ns_lock = ['packages' => []];
        }


        $ns_lock['packages'][$app_name] = [
            'name' => $app_config['name'],
            "type" => "dir",
            'path' => $app_name,
            'setting' => $app_config
        ];

        $_has = file_put_contents(ROOT_PATH . "/app/ns.lock", json_encode($ns_lock, JSON_UNESCAPED_SLASHES));
        if ($_has) {
            $this->output->success("The ns.lock file dependency was updated successfully");
            $this->output->success("successfully");
        } else {
            $this->output->error("Dependency update failed for ns.lock files");
        }
    }
}
