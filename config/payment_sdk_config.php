<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-7
 * Time: 上午9:42
 */
//#########################【 配置信息 】##################################
return [
	//平台名称
	'platform' => 'qupai',
	//本地数据库里面的银行编号(左边)名与第三方的编号(右边)
	'banks' => [
		'ICBC' => 'ICBK',
		'CCB' => 'PCBC',
		'ABC' => 'ABOC',
		'BOC' => 'BKCH',
		'CMB' => 'CMBC',
		'BOCOM' => 'COMM',
		'CMBC' => 'MSBC',
		'CITIC' => 'CIBK',
		'SPDB' => 'SPDB',
		'GDB' => 'GDBK',
		'PAB' => 'SZDB',
		'CIB' => 'FJIB',
		'HXB' => 'HXBK',
		'CEB' => 'EVER',
		'PSBC' => 'PSBC',
	],
    'net_banks' => [
        'QQ' => 'qq',
        'SHENGPAY' => '',//盛付通
        'TENPAY' => '', //财付通
        'WEIXIN' => 'weixin',//微信
        'YLZF' => 'unionpay',//银联支付
        'ALIPAY'=> 'alipay' //支付宝
    ],
	//第三方支付平台的链接配置
	'lgv_pay_url' => [
		'base_url' => 'https://qpyl.uas-gw.info/',
		'methods_url' => 'deposit/qupai/methods',
		'forward_url' => 'deposit/qupai/forward',
        'notify_url' => 'deposit/qupai/~channel~/notify',
	],
	//配置curl http 的时间毫秒
	'connection_time' => [
		'millisecond' => 3000,
	],
	//日志记录用的时间戳
	'logging_timezone' => [
		'city' => '北京',
		'timezone' => 'Asia/Shanghai',
	],
	//PHP 应用扩展检查配置
	'pay_need_extensions' => ['curl', 'openssl'],
    //open_ssl 解密配置
    'decrypt'=>[
        'method'=>'AES-256-CBC',
        'password'=>'Lk6nNYlBaA2L/87h0eXRXMnfKv8kakaESh3JwoOUbaY0tZR7eycyDzbkRB+JIuDD',
        'options'=>OPENSSL_RAW_DATA,
        'iv'=>'8D0C7xA1Pfy3Ml+6',
    ]
];