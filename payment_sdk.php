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
require_once $sdk_path . '/common/Payment_sdk_common.php';

trait Payment_sdk
{
    /**
     * 获取目前开启的所有支付渠道 平台调用
     * @return array|mixed|string
     */
    public function get_payment_setting()
    {
        header("Content-Type:text/html;charset=utf-8");
        $url = $this->lgvpay_methods_url;
        if (is_array($url = $this->validate_valid_url($url))) {
            return $url;
        }
        $result = $this->httpGet($url);
        if (!empty($result) && !isset($result['error_msg'])) {
            $payment_response = json_decode($result, true);
            //#################################
            //获取到第三方 开启的 数据
            if (!is_null($payment_response) && isset($payment_response['data'])) {
                $payment_response_data = $payment_response['data'];
                $final_data['gate_ways'] = [];
                $final_data['gate_ways_cnname'] = [];
                $final_data['gate_ways_image'] = [];
                foreach ($payment_response_data as $key1 => $value1) {//value2 is key inside of data
                    if (isset($value1['gateway'])) {
                        array_push($final_data['gate_ways'], $value1['gateway']);
                        if ($value1['gateway'] == 'banks') {
                            //loop banks array
                            foreach ($value1['banks'] as $key2 => $value2) {//value2 is key inside of banks
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
                return $this->error_return($this->errors_filer['third_party_data_error']);
            }
        } else {
            $this->marker = __FUNCTION__;
            return $this->error_return($this->errors_filer['third_party_data_empty']);
        }
    }

    /**
     * 提交订单生成订单数据
     * @param $order_params
     * @return mixed
     */
    public function payment_forward($order_params)
    {
        $payment_data_json = $this->get_payment_setting();
        if (!empty($payment_data_json) && !isset($payment_data_json['error_msg'])) {
            $payment_data_arr = json_decode($payment_data_json, true);
            extract($payment_data_arr);
            $deposit_order = $order_params;
            $deposit_order['ip'] = $this->get_client_ip(); //获取用户IP地址
            //##################【 准备要提交到第三方平台 】#################################
            $forward_arr = [
                'return_url' => $deposit_order['return_url'],
                'order_no' => $deposit_order['order_no'],
                'amount' => number_format($deposit_order['amount'], 2, '.', ''),
                'gateway' => $deposit_order['gateway'],
                'ip' => $deposit_order['ip'],
            ];
            if (isset($order_params['bank'])) //如果是 网银快捷就加银行编码
            {
                $forward_arr['bank'] = $deposit_order['bank'];
            }
            header("Content-Type:text/html;charset=utf-8");
            $url = $this->lgvpay_forward_url;
            $result = $this->httpPost($url, $forward_arr);
            if (!empty($result) && !isset($result['error_msg'])) {
                $payment_response = json_decode($result, true);
                if (isset($payment_response['data'])) {
                    if (isset($payment_response['data']['form'])) {
                        $this->buildForm($payment_response['data']);
                    } else {
                        if (isset($payment_response['data']['url'])) {
                            return $payment_response['data']['url'];
                        } else {
                            return $this->error_return($this->errors_filer['third_party_no_qr_code']);
                        }
                    }
                } else {
                }
            } else {
                $this->marker = __FUNCTION__;
                return isset($result['error_msg']) ? $this->error_return($result['error_msg']) : $this->error_return($this->errors_filer['third_party_payment_confirm_error']);
            }
        } else {
            $this->marker = __FUNCTION__;
            return isset($payment_data_json['error_msg']) ? $this->error_return($payment_data_json['error_msg']) : $this->error_return($this->errors_filer['third_party_data_error']);
        }
    }

    /**
     * 生成平台充值订单号
     * @param $gateway
     * @return string
     */
    public function getDepositOrderNum($gateway = '')
    {
        // return $this->order_prefix . uniqid(mt_rand());
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