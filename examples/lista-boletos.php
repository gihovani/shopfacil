<?php

$merchantId = '';
$merchantKey = '';
$merchantEmail = '';

$shopfacil = new Bradesco\Shopfacil\Shopfacil($merchantId, $merchantKey, $merchantEmail);
$api = $shopfacil->serviceGetOrderListPayment();
print json_encode($api);
