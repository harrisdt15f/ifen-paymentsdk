<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-9-25
 * Time: 下午4:39
 */

namespace Payment_sdk;
require_once __DIR__.'/swithch_load_function.php';

class Finance_Switcher
{
    private $version, $current_path, $sdk_dir, $switch_config, $availablev, $thirdpary;

    private $switch_class = 'Thirdparty_payment';

    /**
     * PaymentSwitcher constructor.
     * @param null $version
     */
    public function __construct($version = null)
    {
        $this->init_param($version);
        $this->check_version();
        $this->get_select_finance();
    }

    /**
     * 初始变量及配置
     * @param $version
     */
    private function init_param($version)
    {
        $this->current_path = get_current_path(__FILE__);
        $this->sdk_dir = $this->get_sdk_dir();
        $this->switch_config = $this->get_config();
        $this->availablev = $this->switch_config['versions'];
        $this->set_sdk_version($version);

    }

    /**
     * @param $version
     */
    private function set_sdk_version($version)
    {
        $this->version = is_null($version) ? $this->search_arr_value(true, 'default', $this->availablev) : $version;
        if (is_null($this->version) && empty($this->version)) {
            $this->error_json('sdk需要开启版本');
        }
    }

    /**
     * 获取配置信息
     * @return mixed
     */
    private function get_config()
    {
        $path = $this->current_path . 'switch_config.php';
        return require_once $path;
    }

    /**
     * 获取sdk目录
     * @return array
     */
    private function get_sdk_dir()
    {
        $path = $this->current_path;
        //$dirs = glob($path . '*' , GLOB_ONLYDIR);
        //$dirs = array_filter(glob('*'), 'is_dir');
        $dir = new \DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $dirs[] = $fileinfo->getFilename();
            }
        }
        $filter = preg_quote('sdk', '~'); // get only concert with sdk
        $dirs = preg_grep('~' . $filter . '~', $dirs);
        return array_values(array_filter($dirs));
    }

    /**
     *检查版本是否正确
     */
    private function check_version()
    {
        $path = $this->current_path;
        $class_name = $this->switch_class;
        if (isset($this->availablev[$this->version]['dir'])) {
            $subdir = $this->availablev[$this->version]['dir']; //sdk_v1
        } else {
            $this->error_json('请配置sdk版本');
        }
        $dir_status = in_array($subdir, $this->sdk_dir);
        if ($dir_status !== true) {
            $this->error_json('没有sdk目录');
        }
        load_class($class_name, $path, $subdir);
        $class = __NAMESPACE__ . '\\' . $this->availablev[$this->version]['namespace'] . '\\' . $class_name;
        try {
            $instance = new $class();
            $versions = $instance->get_sdk_version();
            if ($this->version != $versions) {
                $this->error_json('请检查sdk版本配置');
            } else {
                $this->thirdpary = $instance;
            }
        } catch (\Exception $e) {
            $this->error_json($e->getMessage());
        }
    }

    /**
     * 查询版本
     * @param $match
     * @param $needle
     * @param $array
     * @return int|null|string
     */
    private function search_arr_value($match, $needle, $array)
    {
        foreach ($array as $key => $val) {
            if ($val[$needle] === $match) {
                return $key;
            }
        }
        return null;
    }

    /**
     * 错误json输出
     * @param array $error
     */
    private function error_json($error = [])
    {
        if (is_array($error) || is_object($error)) {
            $result = json_encode($error);
        } else if (is_string($error)) {
            $arr['error_msg'] = $error;
            $result = json_encode($arr);
        }
        echo $result;
        die();
    }

    /**
     * 返回 Thirdpardpayment obj
     * @return mixed
     */
    public function get_select_finance()
    {
        return $this->thirdpary;
    }
}