<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-14
 * Time: 下午5:36
 */
include_once('../Thirdparty_payment.php');
$aa = new Thirdparty_payment();
$result = $aa->get_payment_setting();
//$result = json_decode($result, true);
echo '<pre>' . print_r($result, 1) . '</pre>';
die();