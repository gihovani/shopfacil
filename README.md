#Bradesco ShopFácil

MEIOS DE PAGAMENTO BRADESCO BOLETO BANCÁRIO

### Installation

To install Bradesco ShopFácil, simply:

    $ composer require gihovani/shopfacil
    or
    $ composer require gihovani/shopfacil:dev-master

For latest commit version:

    $ composer require gihovani/shopfacil @dev

### Requirements

Bradesco ShopFácil works with PHP7+.

### Doação / Donate
Doar com PagSeguro
https://pag.ae/bkWHjW



### Consultoria / consulting

https://gg2.com.br

### Exemplo / Example

```php
require __DIR__ . '/vendor/autoload.php';

use Bradesco\Shopfacil\Shopfacil as Shopfacil;

$merchantId = 'preencha aqui';
$merchantKey = 'preencha aqui';
$merchantEmail = 'contato@gg2.com.br';

$shopfacil = new Shopfacil($merchantId, $merchantKey, $merchantEmail);

// Gerar Boleto
$data = $shopfacil->serviceBuildBillet(2, 25.00, '', 'Gihovani Demetrio', '041.843.018-78', '88106-000', 'SC', 'São José', 'Picadas do Sul', 'Rua Luiz Fagundes', '2270');
var_dump(data);
```

### Exemplo de Requisição (JSON)

```json
{
   "merchant_id":"200001073",
   "meio_pagamento":"300",
   "pedido":{
      "numero":6,
      "valor":"2223",
      "descricao":""
   },
   "comprador":{
      "nome":"Gihovani Demetrio",
      "documento":"04184301878",
      "ip":"",
      "user_agent":"",
      "endereco":{
         "cep":"88106000",
         "logradouro":"Rua Luiz Fagundes",
         "numero":"2270",
         "complemento":"",
         "bairro":"Picadas do Sul",
         "cidade":"S\u00e3o Jos\u00e9",
         "uf":"SC"
      }
   },
   "boleto":{
      "beneficiario":"GG2 LTDA",
      "carteira":"25",
      "nosso_numero":"00000006001",
      "data_emissao":"2020-07-25",
      "data_vencimento":"2020-07-30",
      "valor_titulo":"2223",
      "url_logotipo":"https:\/\/gg2.com.br\/site\/images\/logo-arte.png",
      "mensagem_cabecalho":"Boleto GG2",
      "tipo_renderizacao":"2",
      "registro":null,
      "instrucoes":{
         "instrucao_linha_1":"",
         "instrucao_linha_2":"",
         "instrucao_linha_3":"",
         "instrucao_linha_4":"",
         "instrucao_linha_5":"",
         "instrucao_linha_6":"",
         "instrucao_linha_7":"",
         "instrucao_linha_8":"",
         "instrucao_linha_9":"",
         "instrucao_linha_10":"",
         "instrucao_linha_11":"",
         "instrucao_linha_12":""
      }
   },
   "token_request_confirmacao_pagamento":"MDAwMDAwMDYwMDE="
}
```

### Exemplo de Resposta (JSON)

```json
{
   "merchant_id":"200001073",
   "meio_pagamento":"300",
   "pedido":{
      "numero":"6",
      "valor":2223,
      "descricao":""
   },
   "boleto":{
      "valor_titulo":2223,
      "data_geracao":"2020-07-25T17:15:01",
      "linha_digitavel":"23790001245000000060901123456707483320000002223",
      "linha_digitavel_formatada":"23790.00124  50000.000609  01123.456707  4  83320000002223",
      "token":"RlJSdEtnd2RjTGVjNHo3RlA1eVllYm9QejE3NHFVVFliSDJ3QU1LUXpMaz0.",
      "url_acesso":"https:\/\/homolog.meiosdepagamentobradesco.com.br\/apiboleto\/Bradesco?token=RlJSdEtnd2RjTGVjNHo3RlA1eVllYm9QejE3NHFVVFliSDJ3QU1LUXpMaz0."
   },
   "status":{
      "codigo":0,
      "mensagem":"REGISTRO EFETUADO COM SUCESSO - CIP NAO CONFIRMADA"
   }
}
```

```
// Consultas lista de Pedidos
$data = $shopfacil->serviceGetOrderListPayment();
var_dump(data);
```

### Exemplo de Requisição (URL + PRECISA DE AUTENTICACAO)

```
https://homolog.meiosdepagamentobradesco.com.br/SPSConsulta/GetOrderListPayment/200001073/boleto?token=cd5af6744bf083e66a24675fa81745f79d8d7d60&dataInicial=2020/07/20&dataFinal=2020/07/26&status=0&offset=1&limit=10

```

### Exemplo de Resposta (JSON)

```json
{
   "status":{
      "codigo":0,
      "mensagem":"OPERACAO REALIZADA COM SUCESSO"
   },
   "token":{
      "token":"cd5af6744bf083e66a24675fa81745f79d8d7d60",
      "dataCriacao":"25\/07\/2020 15:57:13"
   },
   "pedidos":[
      {
         "numero":"1",
         "valor":"250000",
         "data":"25\/07\/2020 15:38:51",
         "dataPagamento":"25\/07\/2020 16:50:34",
         "linhaDigitavel":"0",
         "status":"10",
         "erro":"0"
      },
      {
         "numero":"2",
         "valor":"2500",
         "data":"25\/07\/2020 16:53:49",
         "linhaDigitavel":"0",
         "status":"10",
         "erro":"0"
      },
      {
         "numero":"3",
         "valor":"2500",
         "data":"25\/07\/2020 17:10:24",
         "linhaDigitavel":"0",
         "status":"10",
         "erro":"0"
      },
      {
         "numero":"4",
         "valor":"12200",
         "data":"25\/07\/2020 17:12:02",
         "linhaDigitavel":"0",
         "status":"10",
         "erro":"0"
      },
      {
         "numero":"6",
         "valor":"2223",
         "data":"25\/07\/2020 17:15:01",
         "linhaDigitavel":"0",
         "status":"10",
         "erro":"0"
      }
   ],
   "paging":{
      "limit":10,
      "currentOffset":1,
      "nextOffset":-1
   }
}
```

```
// Consultar um pedido
$data = $shopfacil->serviceGetOrderById(1);
var_dump(data);
```

### Exemplo de Requisição (URL + PRECISA DE AUTENTICACAO)

```
https://homolog.meiosdepagamentobradesco.com.br/SPSConsulta/GetOrderById/200001073?token=cd5af6744bf083e66a24675fa81745f79d8d7d60&orderId=1

```

### Exemplo de Resposta (JSON)

```json
{
   "status":{
      "codigo":0,
      "mensagem":"OPERACAO REALIZADA COM SUCESSO"
   },
   "token":{
      "token":"cd5af6744bf083e66a24675fa81745f79d8d7d60",
      "dataCriacao":"25\/07\/2020 15:57:13"
   },
   "pedidos":[
      {
         "numero":"1",
         "valor":"250000",
         "data":"25\/07\/2020 15:38:51",
         "linhaDigitavel":"0",
         "status":"10",
         "erro":"0"
      }
   ]
}
```


### Site

https://gg2.com.br

### Esse é um fork do projeto

https://github.com/gilcierweb/shopfacil
