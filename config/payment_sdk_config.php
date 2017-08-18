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
	'platform' => 'bomao',
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
	//第三方支付平台的链接配置
	'lgv_pay_url' => [
		'base_url' => 'http://api.lgvpay/',
		'methods_url' => 'deposit/bomao/methods',
		'forward_url' => 'deposit/bomao/forward',
	],
	//配置curl http 的时间毫秒
	'connection_time' => [
		'millisecond' => 1000,
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
        'password'=>'+OSqlHSlaH1JgXLc+6g4X3iIp2jQJSOF28lSEpE4CZgLQ5E27MBtbxIhrrXvBUxG',
        'options'=>OPENSSL_RAW_DATA,
        'iv'=>'8D0C7xA1Pfy3Ml+6',
    ]
];