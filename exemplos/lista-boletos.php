<?php
require_once 'conf.php';
$shopfacil = new Bradesco\Shopfacil\Shopfacil($merchantId, $merchantKey, $merchantEmail);
$data = $shopfacil->serviceGetOrderListPayment();
var_dump($data);
