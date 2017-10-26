<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-5
 * Time: 下午4:43
 */
$var_explode = explode('/', __FILE__);
array_pop($var_explode);
$sdk_path = '';
foreach ($var_explode as $number) {
    $sdk_path .= $number . '/';
}
require_once $sdk_path . '../common/Payment_sdk_common.php';

trait Payment_sdk
{
    /**
     * 获取目前开启的所有支付渠道 平台调用
     * @param array $level_data
     * @return string
     * @throws Exception
     */
    public function get_payment_setting($level_data = [])
    {
        header("Content-Type:text/html;charset=utf-8");
        $level_query = '';
        if (!empty($level_data))
        {
            if (is_array($level_data) && is_array($level_data['ranks']) && isset($level_data['ranks']) && !empty($level_data['ranks'])) {
                foreach ($level_data['ranks'] as $k => $v) {
                    $rules[$k] = 'required|regex,/^[123456]$/';
                }
                $validated_array = $this->easy_valid($level_data['ranks'], $rules,$error_status);
                //################
                if ($error_status === true) {
                    $this->marker = __FUNCTION__;
                    $error_msg = $this->sdk_throw_error($validated_array, true, 'ranks_invalid');
                    return $error_msg;
                } else {
                    $level = [
                        'ranks' => $level_data['ranks']
                    ];
                    $level_query = '?' . http_build_query($level);
                }
                //################
            }
            else{
                $this->marker = __FUNCTION__;
                    $error_msg = $this->sdk_throw_error($level_data, true, 'ranks_invalid');
                    return $error_msg;

            }
        }
        $url = $this->lgvpay_methods_url . $level_query;
        if (is_array($url = $this->validate_sdk_url($url))) {
            return $url;
        }
        $result = $this->httpGet($url);
        $result = json_decode($result, true);
        if (!empty($result) && !isset($result['error_msg'])) {
            $payment_response = $result;
            $method_log['payment_response'] = $this->json_en_uni($payment_response, true);
            $method_log['log_name'] = 'payment_setting';
            $this->log_args_write($method_log);
            //##############[ first check if it's with ok and data ]############
            $rules = [
                'ok' => 'required',
                //'data' => 'required',
            ];
            $result_valid = $this->easy_valid($payment_response, $rules, $error_status);
            if ($error_status === true) {
                return $result_valid;
            } else {
                $payment_response = $result_valid;
            }
            //#################################
            //获取到第三方 开启的 数据
            if (!is_null($payment_response)) {
                $payment_response_data = $payment_response['data'];
                $final_data['gate_ways'] = [];
                $final_data['gate_ways_cnname'] = [];
                $final_data['gate_ways_image'] = [];
                foreach ($payment_response_data as $key1 => $value1) {
//value2 is key inside of data
                    if (isset($value1['gateway'])) {
                        array_push($final_data['gate_ways'], $value1['gateway']);
                        if ($value1['gateway'] == 'banks') {
                            //loop banks array
                            foreach ($value1['banks'] as $key2 => $value2) {
//value2 is key inside of banks
                                $value2['currency_min'] = isset($value1['limits']['min']) ? $value1['limits']['min'] : 0;
                                $value2['currency_max'] = isset($value1['limits']['max']) ? $value1['limits']['max'] : 0;
                                $value2['tips'] = $value1['tips'];
                                unset($value2['limits']);
                                $final_data['payment_setting_data']['banks'][$value2['code']] = $value2;
                            }
                        } else {
                            $value1['currency_min'] = isset($value1['limits']['min']) ? $value1['limits']['min'] : 0;
                            $value1['currency_max'] = isset($value1['limits']['max']) ? $value1['limits']['max'] : 0;
                            unset($value1['limits']);
                            $final_data['payment_setting_data'][$value1['gateway']] = $value1;
                        }
                    }
//              if (isset($value1['name'])) {  //暂时注释
                    array_push($final_data['gate_ways_cnname'], $value1['name']);
//              }
                    $final_data['available_gateway_and_name'] = array_combine($final_data['gate_ways'], $final_data['gate_ways_cnname']); //for $aTabList usage in bank_tab blade
                    $final_data['banks_for_sync'] = $this->banks_sync;
                    $final_data['net_banks_for_sync'] = $this->net_banks_sync;
                    $final_data['order_prefix'] = $this->order_prefix;
                }
                unset($final_data['gate_ways_cnname'], $final_data['gate_ways_image']);
                return json_encode($final_data);
            } else {
                $this->marker = __FUNCTION__;
                $error_msg = $this->sdk_throw_error($payment_response, true, 'third_party_data_error');
                return $error_msg;
            }
            //##############################################
        } else {
            $this->marker = __FUNCTION__;
            $error_msg = $this->sdk_throw_error($result, true, 'third_party_data_empty');
            return $error_msg;
        }
    }

    /**
     * 提交订单生成订单数据
     * @param array $order_params
     * @return mixed
     * @throws Exception
     */
    public function payment_forward($order_params = [])
    {
        //########################################
        $rules = [
            'return_url' => 'required|valid_url',
            'order_no' => 'required|min_len,17|max_len,25',
            'amount' => 'required|float',
            'gateway' => 'required|alpha',
        ];
        $filters = [
            'return_url' => 'trim|sanitize_string',
            'order_no' => 'trim',
            'amount' => 'trim',
            'gateway' => 'trim',
        ];
        if (isset($order_params['gateway']) && $order_params['gateway'] == 'banks') {
            $rules['bank'] = 'required|alpha|min_len,4|max_len,6';
            $filters['bank'] = 'trim';
        }
        $order_params = $this->filter($order_params, $filters);
        //##############################
        if (is_array($order_params['ranks']) && isset($order_params['ranks']) && !empty($order_params['ranks']))
        {
            foreach ($order_params['ranks'] as $k => $v) {
                $rank_rules[$k] = 'required|regex,/^[123456]$/';
            }
            $validated_array = $this->easy_valid($order_params['ranks'], $rank_rules,$error_status);
            //################
            if ($error_status === true) {
                $this->marker = __FUNCTION__;
                $error_msg = $this->sdk_throw_error($validated_array, true, 'ranks_invalid');
                return $error_msg;
            } else {
                $order_params = $this->easy_valid($order_params, $rules, $error_status);
                if ($error_status === true) {
                    return $order_params;
                }
            }
            //################
        }
        else{
            $this->marker = __FUNCTION__;
            $error_msg = $this->sdk_throw_error($order_params, true, 'ranks_invalid');
            return $error_msg;
        }
        //#######################################
        $order_params['ip'] = $this->get_client_ip(); //获取用户IP地址
        $amount = 0.0;
        if (isset($order_params['amount'])) {
            $amount = empty($order_params['amount']) ? $amount : number_format($order_params['amount'], 2, '.', '');
        }
        //##################【 准备要提交到第三方平台 】#################################
        $forward_arr = [
            'return_url' => $order_params['return_url'],
            'order_no' => $order_params['order_no'],
            'amount' => $amount,
            'gateway' => $order_params['gateway'],
            'ip' => $order_params['ip'],
        ];
        if (isset($order_params['bank'])) //如果是 网银快捷就加银行编码
        {
            $forward_arr['bank'] = $order_params['bank'];
        }
        if (isset($order_params['ranks']))
        {
            $forward_arr['ranks'] =$order_params['ranks'];
        }
        header("Content-Type:text/html;charset=utf-8");
        $url = $this->lgvpay_forward_url;
        $method_log = $order_params;
        $method_log['url'] = $url;
        $method_log['log_name'] = 'forward_log';
        $this->log_args_write($method_log);
        $result = $this->httpPost($url, $forward_arr);
        $result = json_decode($result, true);
        if (!empty($result) && !isset($result['error_msg'])) {
            $payment_response = $result;
            //######################
            $rules = [
                'ok' => 'required',
                'data' => 'required',
            ];
            $payment_response = $this->easy_valid($payment_response, $rules, $error_status);
            if ($error_status === true) {
                return $payment_response;
            }
            //######################
            if (isset($payment_response['data']['form'])) {
                //#########[校验]#############
                $payment_response = $payment_response['data'];
                $rules = [
                    'method' => 'required|alpha|min_len,3|max_len,4', //GET,POST
                    'url' => 'required',
                ];
                $declare_chinese = [
                    'url' => '支付跳转链接',
                ];
                $payment_response = $this->easy_valid($payment_response, $rules, $error_status, $declare_chinese);
                if ($error_status === true) {
                    return $payment_response;
                }
                //######################
                $this->buildForm($payment_response);
            } else {
                //#########[校验]#############
                $payment_response = $payment_response['data'];
                if ($payment_response === false) //data本是数组不应该返回false 现在返回false 所以这里配合改进
                {
                    $error_msg = $this->sdk_throw_error($result, true, 'channels_is_unavailable');
                    return $error_msg;
                } else {
                    $rules = [
                        'url' => 'required',
                    ];
                    $declare_chinese = [
                        'url' => '扫码链接',
                    ];
                    $payment_response = $this->easy_valid($payment_response, $rules, $error_status, $declare_chinese);
                    if ($error_status === true) {
                        return $payment_response;
                    }
                    //######################
                    return json_encode($payment_response); //$payment_response['url']
                }

            }
        } else {
            $this->marker = __FUNCTION__;
            $error_msg = $this->sdk_throw_error($result, true, 'third_party_payment_confirm_error');
            return $error_msg;
        }
        //#########################################
    }

    /**
     * 生成平台充值订单号
     * @param $gateway
     * @return string
     */
    public function getDepositOrderNum($gateway = '')
    {
        switch ($gateway) {
            case 'banks':
                $order_no = $this->order_prefix . 'BK' . $this->RandomString(3) . time();
                break;
            case 'weixin':
                $order_no = $this->order_prefix . 'WX' . $this->RandomString(3) . time();
                break;
            case 'unionpay':
                $order_no = $this->order_prefix . 'UN' . $this->RandomString(3) . time();
                break;
            case 'alipay':
                $order_no = $this->order_prefix . 'AL' . $this->RandomString(3) . time();
                break;
            case 'qq':
                $order_no = $this->order_prefix . 'QQ' . $this->RandomString(3) . time();
                break;
            case 'baidu':
                $order_no = $this->order_prefix . 'BD' . $this->RandomString(3) . time();
                break;
            case 'jd':
                $order_no = $this->order_prefix . 'JD' . $this->RandomString(3) . time();
                break;
            default:
                $order_no = $this->order_prefix . $this->RandomString() . time();
                break;
        }
        return $order_no;
    }

    private function RandomString($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * 生成form 表单。
     * @param $data
     */
    private function buildForm($data)
    {
        $sign = isset($data['form']['sign']) ? $data['form']['sign'] : mt_rand();
        $url = $data['url'];
        $sHtml = "<form id='third_pay_{$sign}_submit' name='third_pay_{$sign}_submit' action='" . $url . "' method='" . $data['method'] . "'>";
        foreach ($data['form'] as $key => $val) {
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $sHtml .= "<script>document.forms['third_pay_{$sign}_submit'].submit();</script>";
        echo $sHtml;
        die();
    }

    /**
     * @param $tx_no
     * @param $all_inputs
     * @return mixed
     */
    public function payment_callback($tx_no, $all_inputs)
    {
        $log_to_write['input'] = $this->json_en_uni($all_inputs, true);
        log_args_write($log_to_write);
        $url = str_replace('~tx_no~', $tx_no, $this->lgvpay_notify_url);
        $result = $this->httpPost($url, $all_inputs);
        return $result;
    }

    /**
     * 查询充值订单号与充值平台
     * @param string $order_no
     * @return mixed
     */
    public function deposit_order_search($order_no = '')
    {
        if (empty($order_no)) {
            return null;
        } else {
            $url = str_replace('~tx_no~', $order_no, $this->lgvpay_deposit_order_url);
            $result = $this->httpGet($url);
            return $result;
        }
    }

    /**
     * 回调返回数据时用 openssl解密，密码等在配置文件配置即可
     * @param $content
     * @return array
     */
    public function decrypt_content($content)
    {
        $ctx = base64_decode($content);
        $decrypt = json_decode(openssl_decrypt($ctx, $this->decrypt_method, $this->decrypt_password, $this->decrypt_options, $this->decrypt_iv), true);
        return $decrypt;
    }
}