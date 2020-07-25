<?php

$merchantId = '';
$merchantKey = '';
$merchantEmail = '';

$shopfacil = new Bradesco\Shopfacil\Shopfacil($merchantId, $merchantKey, $merchantEmail);
$api = $shopfacil->serviceGetOrderById(1);
print json_encode($api);
