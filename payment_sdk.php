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
trait Payment_sdk {
	/**
	 * 获取目前开启的所有支付渠道
	 * @return array|mixed|string
	 */
	public function get_payment_setting() {
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
				foreach ($payment_response_data as $key1 => $value1) {
					if (isset($value1['gateway'])) {
						array_push($final_data['gate_ways'], $value1['gateway']);
						if ($value1['gateway'] == 'banks') {
							//loop banks array
							foreach ($value1['banks'] as $key2 => $value2) {
								$value2['currency_min'] = $value2['limits']['min'];
								$value2['currency_max'] = $value2['limits']['max'];
								$value2['tips'] = $value1['tips'];
								unset($value2['limits']);
								$final_data['payment_setting_data']['banks'][$value2['code']] = $value2;
							}
						} else {
							$value1['currency_min'] = $value1['limits']['min'];
							$value1['currency_max'] = $value1['limits']['max'];
							unset($value1['limits']);
							$final_data['payment_setting_data'][$value1['gateway']] = $value1;
						}
					}
//              if (isset($value1['name'])) {  //暂时注释
					array_push($final_data['gate_ways_cnname'], $value1['name']);
//              }
					$final_data['available_gateway_and_name'] = array_combine($final_data['gate_ways'], $final_data['gate_ways_cnname']); //for $aTabList usage in bank_tab blade
					$final_data['banks_for_sync'] = $this->banks_sync;
					$final_data['confirm_internal'] = $this->comfirm_url;
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
	 */
	public function payment_forward($order_params) {
		$payment_data_json = $this->payment_data_json;
		if (!empty($payment_data_json) && !isset($payment_data_json['error_msg'])) {
			$payment_data_arr = json_decode($payment_data_json, true);
			extract($payment_data_arr);
			$local_code = $order_params['bank'];
			$code = isset($banks_for_sync[$local_code]) ? $banks_for_sync[$local_code] : $local_code;
			$min = $payment_setting_data['banks'][$code]['currency_min'];
			$max = $payment_setting_data['banks'][$code]['currency_max'];
			$deposit_order = $order_params;
			$deposit_order['ip'] = $this->get_client_ip(); //获取用户IP地址
			//##################【 准备要提交到第三方平台 】#################################
			$forward_arr = [
				'order_no' => $deposit_order['order_no'],
				'amount' => $deposit_order['amount'],
				'gateway' => $deposit_order['gateway'],
				'bank' => $deposit_order['bank'],
				'ip' => $deposit_order['ip'],
			];
			header("Content-Type:text/html;charset=utf-8");
			$url = $this->lgvpay_forward_url;
			$result = $this->httpPost($url, $forward_arr);
			if (!empty($result) && !isset($result['error_msg'])) {
				$payment_response = json_decode($result, true);
				if (isset($payment_response['data'])) {
					$this->buildForm($payment_response['data']);
				} else {
				}
			} else {
				$this->marker = __FUNCTION__;
				return $this->error_return($this->errors_filer['third_party_payment_confirm_error']);
			}
		} else {
			$this->marker = __FUNCTION__;
			return $this->error_return($this->errors_filer['third_party_data_error']);
		}
	}
	/**
	 * 生成平台充值订单号
	 * @return string
	 */
	public function getDepositOrderNum() {
		return $this->order_prefix . uniqid(mt_rand());
	}
	/**
	 * 生成form 表单。
	 * @param $data
	 */
	private function buildForm($data) {
		$sign = isset($data['form']['sign']) ? $data['form']['sign'] : mt_rand();
		$url = $data['url'];
		$sHtml = "<form id='third_pay_{$sign}_submit' name='third_pay_{$sign}_submit' action='" . $url . "' method='" . $data['method'] . "'>";
		foreach ($data['form'] as $key => $val) {
			$sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
		}
//		$sHtml .= "<input type='submit' value='提交'/>";
//		$sHtml .= "</form>";
        $sHtml.= "<script>document.forms['third_pay_{$sign}_submit'].submit();</script>";
		echo $sHtml;
		die();
	}
	/**
	 * @param $channel
	 * @param $all_inputs
	 * @return mixed
	 */
	public function payment_callback($channel, $all_inputs) {
		$url = $this->lgvpay_baseurl . 'deposit/bomao/' . $channel . '/notify';
		$result = $this->httpPost($url, $all_inputs);
		return $result;
	}

    /**
     * 回调返回数据时用 openssl解密，密码等在配置文件配置即可
     * @param $content
     * @return mixed
     */
    public function decrypt_content($content) {
		$ctx = base64_decode($content);
		$decrypt = json_decode(openssl_decrypt($ctx, $this->decrypt_method, $this->decrypt_password, $this->decrypt_options, $this->decrypt_iv));
		return $decrypt;

	}
}