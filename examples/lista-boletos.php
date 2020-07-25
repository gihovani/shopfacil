<?php

require __DIR__ . '/vendor/autoload.php';

use Bradesco\Shopfacil\Shopfacil as Shopfacil;

$merchantId = '';
$merchantKey = '';
$merchantEmail = '';

$shopfacil = new Shopfacil($merchantId, $merchantKey, $merchantEmail);
$api = $shopfacil->serviceGetOrderListPayment();
print json_encode($api);
