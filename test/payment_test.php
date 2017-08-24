<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-14
 * Time: 下午5:29
 */
include_once('../Thirdparty_payment.php');
$aa = new Thirdparty_payment();
$order_params['bank'] = 'BOC';
$order_params['user_id'] = '123456';
$order_params['deposit_mode'] = 2;
$order_params['amount'] = 5;
$order_params['gateway']= 'banks';
$order_params['order_no']= $aa->getDepositOrderNum();

$result = $aa->payment_forward($order_params);
//$result = json_decode($result, true);
echo '<pre>' . print_r($result, 1) . '</pre>';
die();