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
            'order_no' => $data['order_no'],
            'amount' => $data['amount'],
            'bank' => $data['bank'],
            'bank_province' => $data['bank_province'],
            'bank_city' => $data['bank_city'],
            'bank_branch' => $data['bank_branch'],
            'card_no' => $data['card_no'],
            'card_holder' => $data['card_holder'],
            'holder_phone' => $data['holder_phone'],
            'holder_id' => $data['holder_id'],
            /*'ip' => $this->get_client_ip(),*/
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