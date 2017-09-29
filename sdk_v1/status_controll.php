<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-14
 * Time: 下午5:56
 */
namespace Payment_sdk\sdk_v1;
$var_explode = explode('/', __FILE__);
array_pop($var_explode);
$sdk_path = '';
foreach ($var_explode as $number) {
    $sdk_path .= $number . '/';
}

require_once $sdk_path . '/common/Payment_sdk_common.php';
trait Status_controll {


}