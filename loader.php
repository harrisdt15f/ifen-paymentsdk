<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-9-21
 * Time: 上午10:27
 */

/**
 * 获取当前目录
 * @return string
 */
if(!function_exists("get_current_path")) {
    function get_current_path()
    {
        $var_explode = explode('/', __FILE__);
        array_pop($var_explode);
        $path = '';
        foreach ($var_explode as $number) {
            $path .= $number . '/';
        }
        return $path;
    }
}

/**
 * 查找加载文件
 * @param string $needle
 * @param $haystack
 * @return int|string
 */
if(!function_exists("search_load_class")) {
    function search_load_class($needle = '', $haystack)
    {
        foreach ($haystack as $key => $value) {
            $array_key = array_search($needle, $value);
            if ($array_key !== false) {
                return $key;
            }
        }
    }
}

/**
 * 类加载
 * @param $class_name
 * @param $path
 * @param $subdir
 */
if(!function_exists("load_class")) {
    function load_class($class_name, $path, $subdir)
    {
        $class_file = $path . $subdir . '/' . $class_name . '.php';
        require_once $class_file;
    }
}