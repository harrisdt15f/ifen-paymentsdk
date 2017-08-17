<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-17
 * Time: 下午3:04
 */
include_once '../Thirdparty_payment.php';
$aa = new Thirdparty_payment();

$input = [
    'username' => 'aaa',

];

$rules = [
    'username' => 'required|min:3|max:3',
];

$valid = $aa->validate($input, $rules);

var_dump($valid, $aa->get_arr_Errors());
die();