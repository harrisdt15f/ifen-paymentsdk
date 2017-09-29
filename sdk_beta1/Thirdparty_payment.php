<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-15
 * Time: 下午3:02
 */

/*require_once 'loader.php';*/

namespace Payment_sdk\sdk_beta1;

use Payment_sdk\sdk_beta1\common\Payment_sdk_common;
use Payment_sdk\sdk_beta1\main\King_sanitize;
use Payment_sdk\sdk_beta1\main\Payment_sdk;
use Payment_sdk\sdk_beta1\main\Withdraw_sdk;

//require_once __DIR__ . '/../swithch_load_function.php';
//require_once __DIR__ . '/loader.php';
$class_to_load = ['payment_sdk', 'withdraw_sdk', 'status_controll', 'king_sanitize', 'Payment_sdk_common'];
$folder_nav = [
    'main' => ['payment_sdk', 'withdraw_sdk', 'status_controll', 'king_sanitize'],
    'common' => ['Payment_sdk_common'],
];
foreach ($class_to_load as $class) {
    $subdir = search_load_class($class, $folder_nav);
    if (!empty($subdir)) {
        $path = get_current_path(__FILE__);
        load_class($class, $path, $subdir);
    }
}

class Thirdparty_payment extends Payment_sdk_common
{
    public function __construct()
    {
        parent::__construct();
    }

    use Payment_sdk, Withdraw_sdk, King_sanitize;
}