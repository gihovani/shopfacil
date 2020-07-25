<?php
require_once 'conf.php';
$shopfacil = new Bradesco\Shopfacil\Shopfacil($merchantId, $merchantKey, $merchantEmail);
$data = $shopfacil->serviceGetOrderById(1);
var_dump($data);
