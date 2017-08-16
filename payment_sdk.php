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
		if (!isset($result['error_msg'])) {
			$payment_response = json_decode($result, true);
			//#################################
			//获取到第三方 开启的 数据
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
//				if (isset($value1['name'])) {  //暂时注释
				array_push($final_data['gate_ways_cnname'], $value1['name']);
//				}
				$final_data['available_gateway_and_name'] = array_combine($final_data['gate_ways'], $final_data['gate_ways_cnname']); //for $aTabList usage in bank_tab blade
				$final_data['banks_for_sync'] = $this->banks_sync;
				$final_data['confirm_internal'] = $this->comfirm_url;
				$final_data['order_prefix'] = $this->order_prefix;
			}
			unset($final_data['gate_ways_cnname'], $final_data['gate_ways_image']);
			return json_encode($final_data);
		} else {
			return $result;
		}

	}

    /**
     * 提交订单生成订单数据
     * @param $order_params
     */
    public function payment_forward($order_params) {
        $payment_data_arr = json_decode($this->payment_data_json, true);
        extract($payment_data_arr);
        $local_code = $order_params['bank'];
        if (isset($banks_for_sync[$local_code])) {
            $code = $banks_for_sync[$local_code];
        }
        $min = $payment_setting_data['banks'][$code]['currency_min'];
        $max = $payment_setting_data['banks'][$code]['currency_max'];
        $deposit_order = $order_params;
        $deposit_order['order_no'] = $this->getDepositOrderNum();//生成订单号
        $deposit_order['ip']=$this->get_client_ip();//获取用户IP地址
        $create_deposit = $this->createDeposit($deposit_order);//写入订单号
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
        if (!isset($result['error_msg'])) {
            $payment_response = json_decode($result, true);
        }
        else
        {
            $this->deposit_abnormal($deposit_order);
        }
    }

    /**
     * 生成平台充值订单号
     * @return string
     */
    protected function getDepositOrderNum() {
        return $this->order_prefix . uniqid(mt_rand());
    }

    /**
     * 创建技订单号json
     * @param $data
     */
    protected function createDeposit($data) {
        $order_path = $this->order_path;
        $this->create_directory_path($order_path);
        $file = $order_path . $data['order_no'] . '.json';
        $log = json_encode($data, JSON_PRETTY_PRINT);
        $this->create_log($file, $log);
    }
    /**
     * 订单提交失败更新数据
     * @param $data
     */
    protected function deposit_abnormal($data) {
        $order_path = $this->order_path;
        $order_path = $order_path . $data['order_no'];
        $original_file = $order_path . '.json';
        $file = $order_path . '-fail.json';
        rename($original_file, $file);
    }

}