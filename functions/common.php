<?php

function function_enabled($function): bool
{
    $disabled = explode(',', ini_get('disable_functions'));
    return !in_array($function, $disabled);
}


function getPath($path)
{
    $replace = [
        '__ROOT_PATH__' => ROOT_PATH,
        '%__ROOT_PATH__%' => ROOT_PATH
    ];

    foreach ($replace as $index => $item) {
        $path = str_replace($index, $item, $path);
    }

    return $path;
}


if (!function_exists('array_key_first')) {
    function array_key_first(array &$arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}


/**
 * @param string $prefix
 * @return string
 */
function identification($prefix = ''): string
{
    return md5(uniqid($prefix.rand(1000,9999),true));
}


/**
 * @return string
 */
function get_git_commit(): string
{
    return '@git_commit@';
}


/**
 * @return string
 */
function get_git()
{
    return '@git@';
}


/**
 * @return string
 */
function get_datetime(): string
{
    return '@datetime@';
}


/**
 * 拷贝某文件夹里面的所有文件到另一个文件夹（遍历）
 * @param string $dir 规定要复制的文件夹根目录
 * @param string $todir 定复制文件的目的地
 * @return bool
 */
function copyFileAll($dir = "",$todir = ""): bool
{
    if (!is_dir($dir)) {
        return false;
    }
    if (!is_dir($todir)) {
        mkdir($todir,0777,true);
    }
    $dirArray = scandir($dir);
    foreach ($dirArray as $key => $value) {
        if ($value !== '.' && $value !== '..'){
            if (is_dir($dir.'/'.$value)) {
                copyFileAll($dir.'/'.$value,$todir.'/'.$value);
            }else{
                file_put_contents($todir.'/'.$value,file_get_contents($dir.'/'.$value));
            }
        }
    }

    return true;
}


/**
 * 遍历删除文件夹下面的文件
 * @param string $dir 文件夹路径
 * @return bool 删除是否成功的状态
 */
function delectFileAll($dir): bool
{
    if (!is_dir($dir)) return true;
    $dirArray = scandir($dir);
    foreach ($dirArray as $key => $value) {
        if ($value !== '.' && $value !== '..'){
            if (is_dir("{$dir}/{$value}")){
                delectFileAll("{$dir}/{$value}");
            }elseif (is_file("{$dir}/{$value}")){
                unlink("{$dir}/{$value}");
            }
        }
    }
    rmdir($dir);
    return true;
}

/**
 * 驼峰命名转下划线命名
 * @param $camelCaps
 * @param string $separator
 * @return string
 */
function un_camelize($camelCaps,$separator='_'): string
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}
