# Withdraw SDK 使用

#####  引用存放目录

- 放入项目里面的 lib 目录里面。

  如果支付sdk 已完成配置 无需再配置。配置了 ***Thirdparty_payment.php*** 一个文件就可以使用

  如果还没配置就配置以下。

##### 对于laravel 项目配置

1. 项目的 ***vendor/composer/autoload_classmap.php*** 文件里面申明引用。

   ```
   列如： 'Thirdparty_payment' => $baseDir . '/lib/Payment_sdk/Thirdparty_payment.php',
   ```

2. 项目的 vendor/composer/autoload_static.php 文件里面申明引用。

   ```
   列如： 'Thirdparty_payment' => __DIR__ . '/../..' . '/lib/Payment_sdk/Thirdparty_payment.php',
   ```

------

##### 1.提款提交

函数

```
widthdraw_forward()
```

用法

```
$payment_instance = new Thirdparty_payment();
$payment_data_json = $payment_instance->widthdraw_forward();
```

提交参数

| 参数名         | 详解                              | 值                   |
| ----------- | ------------------------------- | ------------------- |
| forward_arr | 数组传入 ***payment_forward()*** 的值 | ***forward_arr[]*** |

***forward_arr[]***  参数

|      参数名      | 详解                                     | 值                         | 必填项  | 类型           |
| :-----------: | -------------------------------------- | ------------------------- | ---- | ------------ |
|   order_no    | sdk 里面有带函数 直接调用即可 列如。 withdrawl_serial | H918013571652222_qp       | 是    | ***string*** |
|    amount     | 提现金额                                   | 10.00                     | 是    | ***float***  |
|     bank      | 用户开户行                                  | BKCH<br>需要传sdk支付配置里面银行的值。 | 是    | ***string*** |
| bank_province | 开户行所在省份                                | 北京                        | 是    | ***string*** |
|   bank_city   | 开户行所在城市                                | 密云区                       | 是    | ***string*** |
|  bank_branch  | 开户支行名称                                 | 密云支行                      | 是    | ***string*** |
|    card_no    | 银行借记卡卡号                                | 5633123456789999          | 是    | ***long***   |
|  card_holder  | 开户人名字                                  | 万三                        | 是    | ***string*** |
| holder_phone  | 开户人在银行登记的手机号码                          | 18682212004               | 是    | ***int***    |
|   holder_id   | 开户人的身份证号码                              | ''                        | 否    | ***int***    |

----------



##### 1.1 生成提款单 

函数

```
withdrawl_serial()
```

用法

```
$payment_instance = new Thirdparty_payment();
$CompanyOrderNum = $payment_instance->withdrawl_serial();
```

返回值

列如： **H918013571652222_qp**  (***string***)

------

#####  

##### 2. 异步回调时解密数据

```
withdrawl_decrypt_content($content)
```

用法

```
$call_back_instance = new Thirdparty_payment();
$call_back_arr = $call_back_instance->withdrawl_decrypt_content($_POST['content']);
```

参数

| 参数      | 详解             | 值                                        |
| ------- | -------------- | ---------------------------------------- |
| content | 异步回调时支付系统传过来的值 | u4khyEzLPNp7ql8pU3jnFpFqo5hSc48pAPWo3CogRoZtA0SbT0UMavP7IgABat0Ukd8g2bqREs4/2X0MrIQy7gO6Re+GyshFxgNK81AzYOY4vBtVjSSgFz80noAHHrbm83FjsF61hxNTtiE+ELTH2Q== |

解析后值

| 参数       | 详解        | 值                        |
| -------- | --------- | ------------------------ |
| order_no | 平台充值订单号   | H918989777112076_qp      |
| tx_no    | 支付系统充值订单号 | 201709180943210040750504 |
| amount   | 充值成功金额    | 50.00                    |
| status   | 充值状态      | succeed/failed           |



------

##### 3. 支付订单查询

函数

```
withdraw_order_search($order_no)
```

用法

```
$payment_instance = new Thirdparty_payment();
$deposit_search_result = $payment_instance->withdraw_order_search($order_no);
```

参数

| 参数        | 详解                             | 值                        | 必填项  | 类型         |
| --------- | ------------------------------ | ------------------------ | ---- | ---------- |
| $order_no | 此订单为支付系统异步回调时返回来的 txt_no，非平台生成 | 201709180943210040750504 | 是    | ***long*** |

返回值是json 值

{

}

------

##### 