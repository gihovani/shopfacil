<?php

require __DIR__ . '/vendor/autoload.php';

use Bradesco\Shopfacil\Shopfacil as Shopfacil;

$merchantId = '';
$merchantKey = '';
$merchantEmail = '';

$shopfacil = new Shopfacil($merchantId, $merchantKey, $merchantEmail);
$api = $shopfacil->serviceGetOrderById(1);
print json_encode($api);
