<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-14
 * Time: 下午5:53
 */
$var_explode = explode('/', __FILE__);
array_pop($var_explode);
$sdk_path = '';
foreach ($var_explode as $number) {
    $sdk_path .= $number . '/';
}

require_once $sdk_path . '/common/Payment_sdk_common.php';

trait Withdraw_sdk
{
    /**
     * @param $data
     * @return mixed
     */
    public function widthdraw_forward($data)
    {
        $forward_arr = [
            'order_no' => $data['order_no'],// 平台提现订单号
            'amount' => $data['amount'],// 提现金额
            'bank' => $data['bank'],// 用户开户行
            'bank_province' => $data['bank_province'],// 开户行所在省份
            'bank_city' => $data['bank_city'], // 开户行所在城市
            'bank_branch' => $data['bank_branch'],// 开户支行名称
            'card_no' => $data['card_no'],// 银行借记卡卡号
            'card_holder' => $data['card_holder'],// 开户人名字
            'holder_phone' => $data['holder_phone'],// 开户人在银行登记的手机号码
            'holder_id' => $data['holder_id'],// 开户人的身份证号码
            /*'ip' => $this->get_client_ip(), // 发起提现请求的服务器 IP 地址, ipv4 */
        ];
        $url = $this->lgvpay_withdraw_url;
        $result = $this->httpPost($url, $forward_arr);
        if (!empty($result) && !isset($result['error_msg'])) {
            $payment_response = json_decode($result, true);
            if (isset($payment_response['ok'])) {
                return $payment_response['ok'];
            } else {
                return $this->error_return($this->errors_filer['third_party_data_error']);
            }
        } else {
            $this->marker = __FUNCTION__;
            return isset($result['error_msg']) ? $this->error_return($result['error_msg']) : $this->error_return($this->errors_filer['third_party_payment_confirm_error']);
        }
    }

    /**
     * 提款单
     * @return string
     */
    public function withdrawl_serial()
    {
        $fix_year = 2010;
        $year_code = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        //############################
        $Y = date('Y'); //2017
        $inty = intval($Y); //2017
        $interval = $inty - $fix_year; //7
        while (!isset($year_code[$interval])) {
            $interval -= 10;
        }
        $Y = $year_code[$interval]; //H
        //############################
        $m = date('m'); //09
        $hexa = dechex($m); //9
        $m_up = strtoupper($hexa);
        //############################
        $d = date('d'); //07
        //############################
        $t = time();
        $t5 = substr($t, -5);
        //############################
        $m_time = microtime();
        $m_time_2_5 = substr($m_time, 2, 5);
        //############################
        $ran_0_99 = rand(0, 99);
        $ran_2d = sprintf('%02d', $ran_0_99);
        //############################
        $order_sn = $Y . $m_up . $d . $t5 . $m_time_2_5 . $ran_2d . '_' . $this->order_prefix;
        return $order_sn;
    }

    /**
     * 查询充值订单号与充值平台
     * @param string $order_no
     * @return mixed
     */
    public function withdraw_order_search($order_no = '')
    {
        if (empty($order_no)) {
            return null;
        } else {
            $url = str_replace('~tx_no~', $order_no, $this->lgvpay_withdraw_order_url);
            $result = $this->httpGet($url);
            return $result;
        }
    }

    /**
     * 回调返回数据时用 openssl解密，密码等在配置文件配置即可
     * @param $content
     * @return array
     */
    public function withdrawl_decrypt_content($content)
    {
        $ctx = base64_decode($content);
        $decrypt = json_decode(openssl_decrypt($ctx, $this->decrypt_method, $this->decrypt_password, $this->decrypt_options, $this->decrypt_iv), true);
        return $decrypt;
    }

}