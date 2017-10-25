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

require_once $sdk_path . '../common/Payment_sdk_common.php';

trait Withdraw_sdk
{
    /**
     * 提现请求
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function widthdraw_forward($data)
    {
        $cn_char = '/^[a-zA-z\x{4e00}-\x{9fa5}]+$/u';
        //##############################################
        $rules = [
            'order_no' => 'required|min_len,19|max_len,25',// 平台提现订单号 //~^[0-9]+[\.1-9]{1,1}[1-9]+$~
            'amount' => 'required|float|regex,~^[0-9]+(\.[0-9]+)?$~',// 提现金额  ~^[0-9]+(\.[0-9]+)?$~
            'bank' => 'required|alpha|min_len,4|max_len,6', // 用户开户行
            'bank_province' => 'required|regex,' . $cn_char, // 开户行所在省份
            'bank_city' => 'required|regex,' . $cn_char, // 开户行所在城市
            'bank_branch' => 'required|regex,' . $cn_char, // 开户支行名称
            'card_no' => 'required|numeric', // 银行借记卡卡号
            'card_holder' => 'required|regex,' . $cn_char . '|min_len,2|max_len,13', // 开户人名字
            'holder_phone' => 'required|phone_number', // 开户人在银行登记的手机号码
            'holder_id' => 'numeric', // 开户人的身份证号码
        ];
        $filters = [
            'order_no' => 'trim',// 平台提现订单号
            'amount' => 'trim',// 提现金额
            'bank' => 'trim', // 用户开户行
            'bank_province' => 'trim', // 开户行所在省份
            'bank_city' => 'trim', // 开户行所在城市
            'bank_branch' => 'trim', // 开户支行名称
            'card_no' => 'trim', // 银行借记卡卡号
            'card_holder' => 'trim', // 开户人名字
            'holder_phone' => 'trim', // 开户人在银行登记的手机号码
            //'holder_id' => 'trim', // 开户人的身份证号码
        ];
        $declare_chinese = [
            'Order No' => '平台提现订单号',
            'Amount' => '提现金额',
            'Bank' => '用户开户行',
            'Bank Province' => '开户行所在省份',
            'Bank City' => '开户行所在城市',
            'Bank Branch' => '开户支行名称',
            'Card No' => '银行借记卡卡号',
            'Card Holder' => '开户人名字',
            'Holder Phone' => '开户人在银行登记的手机号码',
        ];
        $data = $this->filter($data, $filters);
        $data = $this->easy_valid($data, $rules, $error_status, $declare_chinese);
        if ($error_status === true) {
            return $data;
        }
        //##############################################
        $forward_arr = [
            'order_no' => $data['order_no'], // 平台提现订单号
            'amount' => $data['amount'], // 提现金额
            'bank' => $data['bank'], // 用户开户行
            'bank_province' => $data['bank_province'], // 开户行所在省份
            'bank_city' => $data['bank_city'], // 开户行所在城市
            'bank_branch' => $data['bank_branch'], // 开户支行名称
            'card_no' => $data['card_no'], // 银行借记卡卡号
            'card_holder' => $data['card_holder'], // 开户人名字
            'holder_phone' => $data['holder_phone'], // 开户人在银行登记的手机号码
            'holder_id' => $data['holder_id'], // 开户人的身份证号码
            /*'ip' => $this->get_client_ip(), // 发起提现请求的服务器 IP 地址, ipv4 */
        ];
        $url = $this->lgvpay_withdraw_url;
        $result = $this->httpPost($url, $forward_arr);
        $result = json_decode($result, true);
        if (!empty($result) && !isset($result['error_msg'])) {
            $payment_response = $result;
            if (isset($payment_response['ok'])) {
                return json_encode($payment_response);
            } else {
                return $this->error_return($this->errors_filer['third_party_data_error']);
            }
        } else {
            $this->marker = __FUNCTION__;
            $error_msg = $this->sdk_throw_error($result, true, 'third_party_payment_confirm_error');
            return $error_msg;
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