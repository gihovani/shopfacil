<?php
require_once 'conf.php';
$shopfacil = new Bradesco\Shopfacil\Shopfacil($merchantId, $merchantKey, $merchantEmail);
$data = $shopfacil->serviceBuildBillet(6, 22.23, '', 'Gihovani Demetrio', '041.843.018-78', '88106-000', 'SC', 'São José', 'Picadas do Sul', 'Rua Luiz Fagundes', '2270');
var_dump($data);
