<?php

/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-10
 * Time: 下午4:21
 */

namespace Payment_sdk\sdk_beta1\common;
class Payment_sdk_common
{
    protected $path, $lgvpay_baseurl, $lgvpay_methods_url, $lgvpay_forward_url, $lgvpay_notify_url, $lgvpay_withdraw_url, $lgvpay_deposit_order_url, $lgvpay_withdraw_order_url, $banks_sync, $net_banks_sync, $platform_name, $order_prefix, $errors_filer, $order_path, $marker, $decrypt_method, $decrypt_password, $decrypt_options, $decrypt_iv, $payment_data_json;
    private $sdk_version, $city, $timezone, $millisecond, $sdk_logs_path, $sdk_logs_groups, $pay_need_extension, $skd_pem, $skd_crt;

    /**
     * Payment_sdk_common constructor.
     */
    public function __construct()
    {
        $this->init_params();
        if (($check_extension_result = $this->check_pay_need_extension()) !== true) {
            $this->sdk_throw_error($check_extension_result);
        }
    }

    /**
     * initialize the parameter
     */
    protected function init_params()
    {
        $var_explode = explode('/', __FILE__);
        array_pop($var_explode);
        array_pop($var_explode);
        $sdk_path = '';
        foreach ($var_explode as $number) {
            $sdk_path .= $number . '/';
        }
        $this->path = $sdk_path . "config/"; //配置文件所在目录
        $config = $this->path . 'payment_sdk_config.php'; //配置文件名
        $error_file = $this->path . 'error_info.php'; //错误配置
        $this->order_path = $sdk_path . "deposit_order/";
        $config = require_once $config; //获取配置文件
        $this->sdk_version = $config['version']; //获取版本号
        $main_log_dir = key($config['log']);
        $this->sdk_logs_path = $sdk_path . $main_log_dir . '/'; //存日志路径
        $this->sdk_logs_groups = $config['log'][$main_log_dir];
        $this->skd_pem = $this->path . $config['pem_cert']; //pem 证书 需放入sdk config 目录下
        $this->skd_crt = $this->path . $config['ca_cert']; //ca 证书 需放入sdk config 目录下
        $this->order_prefix = $config['order_prefix']; //订单前缀
        $this->platform_name = $config['platform']; //订单前缀
        //########## 【 url 】##############
        $this->lgvpay_baseurl = $config['lgv_pay_url']['base_url']; //第三方支付平台地址
        $this->lgvpay_methods_url = $this->lgvpay_baseurl . $config['lgv_pay_url']['methods_url']; //第三方支付开启信息获取链接
        $this->lgvpay_forward_url = $this->lgvpay_baseurl . $config['lgv_pay_url']['forward_url'];
        $this->lgvpay_notify_url = $this->lgvpay_baseurl . $config['lgv_pay_url']['notify_url'];
        //第三方支付提款链接
        $this->lgvpay_withdraw_url = $this->lgvpay_baseurl . $config['lgv_pay_url']['withdraw_url'];
        $this->lgvpay_deposit_order_url = $this->lgvpay_baseurl . $config['lgv_pay_url']['deposit_order_search_url'];
        $this->lgvpay_withdraw_order_url = $this->lgvpay_baseurl . $config['lgv_pay_url']['withdraw_order_search_url'];
        //######################################
        //第三方支付平台提交订单地址
        $this->banks_sync = $config['banks']; //第三方平台支付与产品平台银行同步
        $this->net_banks_sync = $config['net_banks']; //第三方平台支付与产品平台数据库中的配置同步
        $this->millisecond = $config['connection_time']['millisecond']; //获取链接毫秒
        $this->city = $config['logging_timezone']['city']; //地区配置
        $this->timezone = $config['logging_timezone']['timezone']; //时间戳配置
        $this->errors_filer = require_once $error_file; //错误信息代替显示配置
        $this->pay_need_extension = $config['pay_need_extensions']; //PHP需要扩展配置
        $this->decrypt_method = $config['decrypt']['method'];
        $this->decrypt_password = $config['decrypt']['password'];
        $this->decrypt_options = $config['decrypt']['options'];
        $this->decrypt_iv = $config['decrypt']['iv'];
    }

    /**
     * @return mixed
     */
    public function get_sdk_version()
    {
        return $this->sdk_version;
    }

    /**
     * @return bool|mixed
     */
    protected function check_pay_need_extension()
    {
        $pay_need_extension = $this->pay_need_extension;
        if (is_array($pay_need_extension)) {
            foreach ($pay_need_extension as $key => $extension_name) {
                $result = $this->check_extension($extension_name);
                if (is_array($result) && isset($result['error_msg'])) {
                    return $result['error_msg'];
                } else {
                    return true;
                }
            }
        }
    }

    /**
     * check extension
     * @param $extension_name
     * @return array
     */
    private function check_extension($extension_name)
    {
        $total_extensions = get_loaded_extensions();
        if (!in_array($extension_name, $total_extensions)) {
            $error_str = $extension_name . $this->errors_filer['extension_not_loaded'];
            return $this->error_return($error_str);
        }
    }

    /**
     * 获取证书
     * @return array
     */
    private function get_perm()
    {
        $pemFile = tmpfile();
        fwrite($pemFile, $this->skd_pem); //the path for the pem file
        $tempPemPath = stream_get_meta_data($pemFile);
        $tempPemPath = $tempPemPath['uri'];
        return $tempPemPath;
    }

    /**
     * @param $url
     * @return array|mixed
     */
    protected function httpGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->millisecond); //timeout in milliseconds
        //############
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->skd_pem);
        curl_setopt($ch, CURLOPT_CAINFO, $this->skd_crt);
        //###########
        $output = curl_exec($ch);
        if (!$output) {
            $this->error_return(curl_error($ch));
            $this->marker = __FUNCTION__;
            $this->curl_header_check($ch, $code);
            $this->sdk_throw_error("请联系客服");
        }
        $return_error = json_decode($output, true);
        $this->marker = __FUNCTION__;
        $info = $this->curl_header_check($ch, $code);
        curl_close($ch);
        return $info === true ? $output : $this->error_return($error = isset($this->errors_filer[$return_error['error']]) ? $this->errors_filer[$return_error['error']] : $this->errors_filer['third_party_url_error']);
    }

    /**
     * @param $url
     * @param $params
     * @return array|mixed
     */
    protected function httpPost($url, $params)
    {
        $postData = '';
        //create name value pairs seperated by &
        foreach ($params as $k => $v) {
            $postData .= $k . '=' . $v . '&';
        }
        $postData = rtrim($postData, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->millisecond); //timeout in milliseconds
        //############
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->skd_pem);
        curl_setopt($ch, CURLOPT_CAINFO, $this->skd_crt);
        //###########
        $output = curl_exec($ch);
        if (!$output) {
            $curl_error = curl_error($ch);
            $this->error_return($curl_error);
            $this->error_return($postData);
            $this->marker = __FUNCTION__;
            $this->curl_header_check($ch, $code);
            $this->sdk_throw_error("请联系客服 原因");
        }
        $return_error = json_decode($output, true);
        $this->marker = __FUNCTION__;
        $info = $this->curl_header_check($ch, $code);
        curl_close($ch);
        //##
        return $info === true ? $output : (isset($this->errors_filer[$return_error['error']]) ? $this->error_return($this->errors_filer[$return_error['error']]) : $this->error_return($this->errors_filer['third_party_url_error']));
    }

    /**
     * @param $connection
     * @param $code
     * @return array|bool
     */
    private function curl_header_check($connection, &$code)
    {
        $info = curl_getinfo($connection);
        if (isset($info['http_code']) && ($code = $info['http_code']) != 200) {
            return $this->error_return($info, $this->marker);
        } else {
            return true;
        }
    }

    /**
     * @param $url
     * @return array
     */
    protected function validate_sdk_url($url)
    {
        if (is_null($url) || empty($url)) {
            return $this->error_return($this->errors_filer['blank_url']);
        }
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $this->error_return($this->errors_filer['url_format_error']);
        } else {
            return $url;
        }
    }
    //############################【错误返回函数】##################################################

    /**
     * @param $error_str
     * @param string $marker
     * @return array
     */
    public function error_return($error_str, $marker = '')
    {
        if (isset($this->marker)) {
            unset($this->marker);
        }
        $suitable_class_and_function = debug_backtrace();
        $error_reflect = array($error_str) ? [
            'error_msg' => $this->json_en_uni($error_str, true),
        ] : [
            'error_msg' => $error_str,
        ];
        $method_log['error_msg'] = is_array($error_str) ? json_encode($error_str) : $error_str;
        $method_log['payment_response'] = json_encode($error_reflect);
        $method_log['log_name'] = 'error_msg';
        $this->log_args_write($method_log);
        //########################Log Marker###########################
        if (empty($marker)) {
            $flc_all['class'] = $suitable_class_and_function[1]['class'];
            $flc_all['function'] = $suitable_class_and_function[1]['function'];
        } else {
            $final = sizeof($suitable_class_and_function) - 1;
            for ($i = $final; $i >= 0; $i--) {
                if ($suitable_class_and_function[$i]['function'] == $marker) {
                    $flc_all['class'] = $suitable_class_and_function[$i]['class'];
                    $flc_all['function'] = $suitable_class_and_function[$i]['function'];
                }
            }
        }
        //########################写日志###########################
        $this->log_args_write($error_reflect, $flc_all);
        //#########################################################
        $error_reflect = json_encode($error_reflect);
        return $error_reflect;
    }

    /**
     * @param $error_data
     * @param bool $json
     * @param string $replace_str
     * @return array|string
     */
    public function sdk_throw_error($error_data, $json = false, $replace_str = '')
    {
        if ($json === false) {
            if (is_array($error_data)) {
                $error_data = str_replace('\"', '', $error_data['error_msg']);
                $error_data = str_replace('"', '', $error_data);
            }
            $error_data = $this->html_error_page($error_data);
            echo $error_data;
            die();
        } else {
            if (isset($error_data['error_msg'])) {
                return json_encode($error_data);
            } else {
                return $this->error_return($this->errors_filer[$replace_str]);
            }

        }

    }

    /**
     * 生成HTML错误信息页面
     * @param string $error_data
     * @return mixed|string
     */
    private function html_error_page($error_data = '')
    {
        $html = <<<html
        <!DOCTYPE html>
        <html>
        <style type="text/css">
        fieldset {
            width:50%;
            margin:2em auto;
            background:#e3e3e3;
            border:2px solid blue;
            border-radius:5px;
            padding:20px;
            position:relative;
        }
        legend {
            font-weight:bold;
            position:absolute;
            left:10px;
            top:-1.1em;
            height:2em;
            line-height:2em;
            padding:0 10px;
        }
        legend:after {
            position:absolute;
            content:" ";
            height:3px;
            left:0;
            right:0;
            top:50%;
            background:#fff;
        }
        legend b {
            position:relative;
            z-index:2;
        }
        #btn{
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        position: absolute;
        color: white;
        background:#3db9ec;
        width:100px;
        height:50px;
        z-index:3;
        }
        </style>
        </head>
        <body>
                <fieldset>
                        <legend><button id="btn" onclick="goBack()">关闭回去</button></legend>
                        <div align="center">错误信息</div>
                        <div align="center"><h1>~error~</h1></div>
                </fieldset>
        <script>
        function goBack() {
            window.close();
        }
        </script>
        </body>
        </html>
html;
        $error_data = str_replace('~error~', $error_data, $html);
        return $error_data;
    }
    //###############################[Loggin]#########################################

    /**
     * array type should be
     * $a1 = ['label'=>'description'];
     * usage log_args_write(array1,array2,....);
     * Create array to write log and get the arguments dynamically and send them to han_log
     * @return array|bool|string
     * author: harris
     */
    protected function log_args_write()
    {
        $log = [];
        $log_gruop_names = $this->sdk_logs_groups;
        $marker = 0;
        $k = '';
        $log_name = '';
        $dir_l = DIRECTORY_SEPARATOR;
        $numArgs = func_num_args();
        $args = func_get_args();
        $derive_dir = '~debugs~' . $dir_l;
        foreach ($args as $index => $arg) {
            if (is_array($arg)) {
                $log_gruop_name = key(array_intersect_key($log_gruop_names, $arg));
                $derive_dir = $log_gruop_name != null ? str_replace('~debugs~', $log_gruop_names[$log_gruop_name], $derive_dir) : str_replace('~debugs~', $log_gruop_names['default'], $derive_dir);
                if (array_key_exists("log_name", $arg)) {
                    $log_name = $arg['log_name'];
                    unset($arg['log_name']);
                    $flc_path = $this->sdk_logs_path . $derive_dir;
                    $this->create_directory_path($flc_path);
                } else {
                    $flc_all = $numArgs > 1 ? array_pop($args) : $args;
                    $flc_str = isset($flc_all['class']) ? $flc_all['class'] . "*" . $flc_all['function'] : 'default';
                    $log_name = isset($flc_all['class']) ? $flc_all['function'] : 'default';
                }
                if (!empty($flc_str)) {
                    if (strpos($flc_str, '*') !== false) {
                        $flc = explode('*', $flc_str);
                        $class_name_dir = $flc[0];
                        /*$subfolder = $flc[1];*/
                        $flc_path = $this->sdk_logs_path . $derive_dir . $class_name_dir;
                    } else {
                        $flc_path = $this->sdk_logs_path . $derive_dir . $log_name;
                    }
                    $this->create_directory_path($flc_path);
                }
                array_push($log, $arg);
                $marker++;
            } else {
                $k = empty($k) ? $index . 'args =' . $arg : $k . "<br>" . $index . 'args =' . $arg;
            }
        }
        if ($marker < sizeof($args)) {
            return $mess = [
                'errMsg' => $k . ' 不是arrray值',
            ];
        }
        $status = $this->arr_2_log($log, $log_name, $flc_path); //writ log
        return $status;
    }

    /**
     * @param $dir_path
     */
    protected function create_directory_path($dir_path)
    {
        if (!file_exists($dir_path)) {
            $oldmask = umask(0);
            mkdir($dir_path, 0777, true);
            umask($oldmask);
        }
    }

    /**
     * get array from the log_args_write() and write to the log dynamically
     * @param array $arr
     * @param string $name
     * @param string $flc_path
     * @return bool|string
     * author: harris
     */
    protected function arr_2_log($arr = [], $name = '', $flc_path = '')
    {
        if (empty($arr)) {
            return "拼接日志数组为空";
        } else {
            $log = '';
            foreach ($arr as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $key => $value) {
                        $log = empty($log) ? $key . " = " . $v = is_array($value) ? json_en_uni($value) . "\n" : $value . "\n" : $log . $key . " = " . $v = is_array($value) ? json_en_uni($value) . "\n" : $value . "\n";
                    }
                    $this->log_with_date($log, $name, $flc_path);
                } else {
                    $this->log_with_date('拼接日志输入string 值请检查参数', $name);
                }
            }
            return true;
        }
    }

    /**
     * Final function of writing log to the specific folder
     * @param $log
     * @param $name
     * @param string $flc_path
     * @return bool
     * author: harris
     */
    protected function log_with_date($log, $name, $flc_path = '')
    {
        if (empty($flc_path)) {
            $flc_path = $this->sdk_logs_path;
        }
        $today = date('Y-m-d H:i:s', time());
        $now = ' ' . $this->city . '时间：' . $this->dynamic_zone_time($today);
        $name = $name . '_' . $this->dynamic_zone_time($today, 'y_m_d');
        $log = "[" . $now . " ]" . "\r\n{$log}####################################################################\r\n";
        $log_path = $flc_path . '/' . $name . '.txt';
        $this->create_log($log_path, $log);
        return true;
    }

    /**
     * 创建日志
     * @param $log_path
     * @param $log
     * @return bool
     */
    protected function create_log($log_path, $log)
    {
        $oldmask = umask(0);
        file_put_contents($log_path, $log, FILE_APPEND);
        umask($oldmask);
        return true;
    }

    /**
     * anytime to china time
     * @param $time must be Y-m-d H:i:s format
     * @param string $format
     * @return string author : Harris
     * author : Harris
     */
    private function dynamic_zone_time($time, $format = 'Y-m-d H:i:s')
    {
        $datetime = new \DateTime($time);
        $cn_time = new \DateTimeZone($this->timezone);
        $datetime->setTimezone($cn_time);
        return $datetime->format($format);
    }

    /**
     * for unescaping uni
     * @param $data
     * @param bool $pretty
     * @return string
     * author: harris
     */
    protected function json_en_uni($data, $pretty = false)
    {
        return $pretty === true ? $this->prettyPrint(json_encode($data, JSON_UNESCAPED_UNICODE)) : json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * prettyPrint
     * @param $json
     * @return string
     * author: harris
     */
    protected function prettyPrint($json)
    {
        $result = '';
        $level = 0;
        $in_quotes = false;
        $in_escape = false;
        $ends_line_level = NULL;
        $json_length = strlen($json);
        for ($i = 0; $i < $json_length; $i++) {
            $char = $json[$i];
            $new_line_level = NULL;
            $post = "";
            if ($ends_line_level !== NULL) {
                $new_line_level = $ends_line_level;
                $ends_line_level = NULL;
            }
            if ($in_escape) {
                $in_escape = false;
            } else if ($char === '"') {
                $in_quotes = !$in_quotes;
            } else if (!$in_quotes) {
                switch ($char) {
                    case '}':
                    case ']':
                        $level--;
                        $ends_line_level = NULL;
                        $new_line_level = $level;
                        break;
                    case '{':
                    case '[':
                        $level++;
                    case ',':
                        $ends_line_level = $level;
                        break;
                    case ':':
                        $post = " ";
                        break;
                    case " ":
                    case "\t":
                    case "\n":
                    case "\r":
                        $char = "";
                        $ends_line_level = $new_line_level;
                        $new_line_level = NULL;
                        break;
                }
            } else if ($char === '\\') {
                $in_escape = true;
            }
            if ($new_line_level !== NULL) {
                $result .= "\n" . str_repeat("\t", $new_line_level);
            }
            $result .= $char . $post;
        }
        return $result;
    }

    /**
     * Get Client user IP Address
     * @return string
     */
    public function get_client_ip()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = '0.0.0.0';
        return $ipaddress;
    }
}