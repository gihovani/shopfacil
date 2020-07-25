<?php

require __DIR__ . '/vendor/autoload.php';

use Bradesco\Shopfacil\Shopfacil as Shopfacil;

$merchantId = '';
$merchantKey = '';
$merchantEmail = '';

$shopfacil = new Shopfacil($merchantId, $merchantKey, $merchantEmail);
$api = $shopfacil->serviceBuildBillet(6, 22.23, '', 'Gihovani Demetrio', '041.843.018-78', '88106-000', 'SC', 'São José', 'Picadas do Sul', 'Rua Luiz Fagundes', '2270');
print json_encode($api);
