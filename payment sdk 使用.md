# Payment SDK 使用

##### 引用存放目录

- 放入项目里面的 lib 目录里面。

##### 对于laravel 项目配置

1. 项目的vendor/composer/autoload_classmap.php 文件里面申明引用。

   ```
   列如： 'Thirdparty_payment' => $baseDir . '/lib/Payment_sdk/Thirdparty_payment.php',
   ```

2. 项目的 vendor/composer/autoload_static.php 文件里面申明引用。

   ```
   列如： 'Thirdparty_payment' => __DIR__ . '/../..' . '/lib/Payment_sdk/Thirdparty_payment.php',
   ```


-----------

##### 文件申明

1. Payment_sdk/Thirdparty_payment.php  （SDK Class 文件）
2. Payment_sdk/payment_sdk_config.php （SDK 配置文件）

---------

##### 配置skd

```
	//平台名称
	'platform' => 'qupai',
    //订单前缀
    'order_prefix' => 'qp',
    //pem 证书 需放入sdk config 目录下
    'pem_cert' => 'client.pem',//pem 证书文件名
    //ca 证书 需放入sdk config 目录下
    'ca_cert' => 'ca.crt',//ca 证书文件名
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
        'QQ' => 'qq', //qq支付
        'SHENGPAY' => '',//盛付通
        'TENPAY' => '', //财付通
        'WEIXIN' => 'weixin',//微信
        'YLZF' => 'unionpay',//银联支付
        'ALIPAY'=> 'alipay' //支付宝
    ],
	//第三方支付平台的链接配置
	'lgv_pay_url' => [
		'base_url' => 'https://qupa.uas-gw.info/', //支付系统地址
		'methods_url' => 'deposit/qupai/methods',//支付系统获取开关地址
		'forward_url' => 'deposit/qupai/forward',//支付系统提交地址
        'notify_url' => 'deposit/qupai/~channel~/notify',//支付系统通知地址 (~channel~) 为动态支付系统返回值
        'withdraw_url' => 'withdrawal/qupai/forward',//支付系统提款地址
        'deposit_order_search_url'=> 'deposit/qupai/order/~tx_no~',//支付系统充值对账查询地址 (~txt_no~) 为动态支付系统返回值
        'withdraw_order_search_url'=> 'withdrawal/qupai/order/~tx_no~',//支付系统提现对账查询地址 (~txt_no~) 为动态支付系统返回值
	],
	//配置curl http 的时间毫秒
	'connection_time' => [
		'millisecond' => 10000,
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
        'password'=>'vCKUplCQBrlRzRNfS2DmKg/KCUofJlJiE6wYsMWxJ9h/Hkw4FsRbEWSM',
        'options'=>OPENSSL_RAW_DATA,
        'iv'=>'8D0C7xA1Pfy3Ml+6',
    ]
```



---------



##### 获取第三方支付信息

函数

```
get_payment_setting()
```

用法

```
$payment_instance = new Thirdparty_payment();
$payment_data_json = $payment_instance->get_payment_setting();
$payment_data_arr = json_decode($payment_data_json, true);
extract($payment_data_arr);
```



获得变量名以下

|             变量名             | 详解        | 值                                        |
| :-------------------------: | --------- | ---------------------------------------- |
|        $order_prefix        | 订单前缀（平台名） | qp                                       |
| $available_gateway_and_name | 当前能用的支付方式 | Array<br>( <br>[banks] => 网银快捷, <br>[unionpay] => 银联快捷,<br>[weixin] => 微信<br>） |
|    $payment_setting_data    | 支付详细信息    | Array<br> (<br>[banks] => Array<br>(<br>银行数据<br>)<br>)<br>[unionpay] => Array<br>(<br>银联数据)<br>[weixin] => Array<br>(<br>微信数据w<br>)<br>) |
|       $banks_for_sync       | 银行支付编码同步  | Array<br> ([ICBC] => ICBK,<br>[CCB] => PCBC,<br>[ABC] => ABOC,<br>[BOC] => BKCH,<br>[CMB] => CMBC,<br>[BOCOM] => COMM,<br>[CMBC] => MSBC,<br>[CITIC] => CIBK,<br>[SPDB] => SPDB,<br>[GDB] => GDBK,<br>[PAB] => SZDB,<br>[CIB] => FJIB,<br>[HXB] => HXBK,<br>[CEB] => EVER,<br>[PSBC] => PSBC<br>) |
|     $net_banks_for_sync     | 非银行支付编码同步 | Array<br> ( [QQ] => [qq],<br><br>[SHENGPAY" => [],<br><br>[TENPAY]=> [],<br><br>[WEIXIN] => [weixin],<br><br>[YLZF] => [unionpay],<br><br>[ALIPAY]=> [alipay]<br>) |



$payment_setting_data

| 参数名      | 详解   | 值                                        |
| -------- | ---- | ---------------------------------------- |
| banks    | 网银信息 | [CMBC] => Array<br>(<br>[code] => CMBC,<br>[name] => 招商银行,<br>[currency_min] => 2,[currency_max] => 100,<br>[tips] => 单日充值总额无上限，充值无手续费 <br>)<br>[ICBK] => Array<br>(<br>[code] => ICBK,<br>[name] => 工商银行,<br>[currency_min] => 2,<br>[currency_max] => 100,<br>[tips] => 单日充值总额无上限，充值无手续费)<br>[BKCH] => Array<br>(<br>[code] => BKCH,<br>[name] => 中国银行,<br>[currency_min] => 2,<br>[currency_max] => 100,<br>[tips] => 单日充值总额无上限，充值无手续费<br>) |
| weixin   | 微信信息 | [gateway] => weixin,<br>[name] => 微信,<br>[tips] => 请在5分钟内完成转账，单日无次数上限,<br>[currency_min] => 2,<br>[currency_max] => 100<br> |
| unionpay | 银联信息 | [gateway] => unionpay,<br>[name] => 银联快捷,<br>[tips] =>,<br>[currency_min] => 2,<br>[currency_max] => 100 |



-------

##### 提交充值

函数

```
payment_forward()
```

用法

```
$payment_instance = new Thirdparty_payment();
$payment_data = $payment_instance->payment_forward();
```

获得变量名以下

|    参数名     | 详解                                       | 值                                        |
| :--------: | ---------------------------------------- | ---------------------------------------- |
|  order_no  | sdk 里面有带函数 直接调用即可 列如。 getDepositOrderNum（'banks'） | qpBK88a1504502831                        |
|   amount   | 充值金额                                     | 10.00                                    |
|  gateway   | 渠道名称                                     | banks                                    |
| return_url | 通知同步回调地址 就是平台域名                          | http://平台域名/同步回调地址<br> 列如：http://www.qupai.com/deposit/return |

-------



##### 生成订单

函数

```
getDepositOrderNum($payment_gateway)
```

用法

```
$payment_instance = new Thirdparty_payment();
$CompanyOrderNum = $payment_instance->getDepositOrderNum($payment_gateway);
```

参数

| 参数              | 详解          | 值                               |
| --------------- | ----------- | ------------------------------- |
| payment_gateway | 用渠道名生成响应的订单 | banks/weixin/unionpay/qq/alipay |

返回值

列如： qpBKSq81504665636  (string)

---



##### 支付系统通知同步回调

函数

```
payment_callback($channel, $all_inputs)
```

用法

```
$payment_instance = new Thirdparty_payment();
$result = $payment_instance->payment_callback($channel, $all_inputs);
```

接收与传送值

把接收到的 以下参数都传给 支付系统。

| 参数          | 详解                         |
| ----------- | -------------------------- |
| $channel    | 支付系统 传过来的 值                |
| $all_inputs | 支付系统 传过来的所有http请求参数 排除 url |

返回值

 ok 

----------

##### 

##### 异步回调时解密数据

```
decrypt_content($content)
```

用法

```
$call_back_instance = new Thirdparty_payment();
$call_back_arr = $call_back_instance->decrypt_content($_POST['content']);
```

参数

| 参数      | 详解             | 值                                        |
| ------- | -------------- | ---------------------------------------- |
| content | 异步回调时支付系统传过来的值 | dmWMRZheEF1s8nG3XBIIx8UOSbQltq/L6XL0IuASUq/f9xN58jk6gJ9W9MAjh5SpxHATangFG4MRQpliIR4ijFkZgPYl4SBr10AlTVKGq/PIMYNambyQ1rVA5gB4hESZpOyjDdllOjxHWp8WWSsHbg== |

解析后值

| 参数       | 详解        | 值                        |
| -------- | --------- | ------------------------ |
| order_no | 平台充值订单号   | qpWXlEA1504663730        |
| tx_no    | 支付系统充值订单号 | 201709061008507300279140 |
| amount   | 充值成功金额    | 4.00                     |
| status   | 充值状态      | succeed/failed           |



-----

##### 支付订单查询

函数

```
deposit_order_search($order_no)
```

用法

```
$payment_instance = new Thirdparty_payment();
$deposit_search_result = $payment_instance->deposit_order_search($order_no);
```

参数

| 参数        | 详解                             | 值                        |
| --------- | ------------------------------ | ------------------------ |
| $order_no | 此订单为支付系统异步回调时返回来的 txt_no，非平台生成 | 201709061040367044150829 |



------

##### 