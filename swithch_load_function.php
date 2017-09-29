<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-9-25
 * Time: 下午4:28
 */

/**
 * 获取当前目录
 * @param string $current
 * @return string
 */
function get_current_path($current = __FILE__)
{
    $var_explode = explode('/', $current);
    array_pop($var_explode);
    $path = '';
    foreach ($var_explode as $number) {
        $path .= $number . '/';
    }
    return $path;
}

/**
 * 查找加载文件
 * @param string $needle
 * @param $haystack
 * @return int|string
 */
function search_load_class($needle = '', $haystack)
{
    foreach ($haystack as $key => $value) {
        $array_key = array_search($needle, $value);
        if ($array_key !== false) {
            return $key;
        }
    }
}

/**
 * 类加载
 * @param $class_name
 * @param $path
 * @param $subdir
 */
function load_class($class_name, $path, $subdir)
{
    $class_file = $path . $subdir . '/' . $class_name . '.php';
    require_once $class_file;
}