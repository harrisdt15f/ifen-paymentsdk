<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-14
 * Time: 下午5:36
 */
include_once '../Thirdparty_payment.php';
$aa = new Thirdparty_payment();
//$result = $aa->get_payment_setting();
//$result = json_decode($result, true);

//{"content":"XTiwbANeyaX/Q56eIq/vAi7HwnohUw0oE/oHHjepoyF6DVPB5SVbNn9xnGhxknqZPzCosHTZRyaPF55FwKlfDjX9fUgWttxttcezOc1oBx4="}

$ctx = base64_decode("XTiwbANeyaX/Q56eIq/vAi7HwnohUw0oE/oHHjepoyF6DVPB5SVbNn9xnGhxknqZPzCosHTZRyaPF55FwKlfDjX9fUgWttxttcezOc1oBx4=");
$decrypt = json_decode(openssl_decrypt($ctx, 'AES-256-CBC', '+OSqlHSlaH1JgXLc+6g4X3iIp2jQJSOF28lSEpE4CZgLQ5E27MBtbxIhrrXvBUxG', OPENSSL_RAW_DATA, '8D0C7xA1Pfy3Ml+6'));
$ctx ="XTiwbANeyaX/Q56eIq/vAi7HwnohUw0oE/oHHjepoyF6DVPB5SVbNn9xnGhxknqZPzCosHTZRyaPF55FwKlfDjX9fUgWttxttcezOc1oBx4=";
$aa->decrypt_content($ctx);
var_dump($decrypt);