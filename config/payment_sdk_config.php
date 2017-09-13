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
    //订单前缀
    'order_prefix' => 'qp',
    //pem 证书 需放入sdk config 目录下
    'pem_cert' => 'c-qpyl.pem',//c-qpyl.pem client.pem
    //ca 证书 需放入sdk config 目录下
    'ca_cert' => 'ca.crt',
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
		'base_url' => 'https://qupa.uas-gw.info/', //https://qpyl.uas-gw.info/ 支付系统地址
		'methods_url' => 'deposit/qupai/methods',//支付系统获取开关地址
		'forward_url' => 'deposit/qupai/forward',//支付系统提交地址
        'notify_url' => 'deposit/notify/~tx_no~',//充值时支付系统通知地址
        //'withdraw_notify_url' => 'withdraw/notify/~tx_no~',//提款时支付系统通知地址
        'withdraw_url' => 'withdrawal/qupai/forward',//支付系统提款地址
        'deposit_order_search_url'=> 'deposit/qupai/order/~tx_no~',//支付系统充值对账查询地址
        'withdraw_order_search_url'=> 'withdrawal/qupai/order/~tx_no~',//支付系统提现对账查询地址
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
        //        'password'=>'Lk6nNYlBaA2L/87h0eXRXMnfKv8kakaESh3JwoOUbaY0tZR7eycyDzbkRB+JIuDD',
        'password'=>'vCKUplCQBrlRzRNfS2DmKg/KCUofJlJiE6wYsMWxJ9h/Hkw4FsRbEWSM',
        'options'=>OPENSSL_RAW_DATA,
        'iv'=>'8D0C7xA1Pfy3Ml+6',
    ]
];