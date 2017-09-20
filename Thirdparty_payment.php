<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-15
 * Time: 下午3:02
 */
$var_explode = explode('/', __FILE__);
array_pop($var_explode);
$sdk_path = '';
foreach ($var_explode as $number) {
	$sdk_path .= $number . '/';
}
require_once $sdk_path . '/payment_sdk.php';
require_once $sdk_path . '/withdraw_sdk.php';
require_once $sdk_path . '/status_controll.php';
require_once $sdk_path . '/common/Payment_sdk_common.php';
require_once $sdk_path . '/king_sanitize.php';
class Thirdparty_payment extends Payment_sdk_common {
	public function __construct() {
		parent::__construct();
	}
	use Payment_sdk, Withdraw_sdk , King_sanitize;
}