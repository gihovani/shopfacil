<?php

namespace Bradesco\Shopfacil;

class Shopfacil
{
    const URL_SANDBOX = 'https://homolog.meiosdepagamentobradesco.com.br';
    const URL_PROD = 'https://meiosdepagamentobradesco.com.br';

    public $sandbox = true;
    private $conf;
    private $merchantId = null;
    private $merchantKey = null;
    private $merchantEmail = null;
    private $order = [];
    private $customer = [];
    private $customerAddress = [];
    private $billet = [];
    private $billetInfo = array();

    private $token = null;

    /**
     * Shopfacil constructor.
     * @param string $merchantId
     * @param string $merchantKey
     * @param string|null $merchantEmail
     */
    public function __construct($merchantId, $merchantKey, $merchantEmail = null)
    {
        $this->merchantId = trim($merchantId);
        $this->merchantKey = trim($merchantKey);
        $this->merchantEmail = trim($merchantEmail);
        $this->_init();;
    }

    public function _init()
    {
        $this->conf = array(
            'dias_vencimento_boleto' => 5,
            'beneficiario' => 'Dental Cremer',
            'carteira' => '25',
            'url_logotipo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Logomarca_Dental_Cremer.png/240px-Logomarca_Dental_Cremer.png',
            'mensagem_cabecalho' => 'Boleto Dental Cremer',
            'tipo_renderizacao' => '2',
        );
    }

    public function serviceBuildBillet(
        $pedidoNumero, $pedidoValor, $pedidoDescricao = '',
        $compradorNome = '', $compradoCpfCnpj = '',
        $compradorCep = '', $compradorUf = '', $compradorCidade = '', $compradorBairro = '', $compradorLogradouro = '', $compradorNumero = '', $compradorComplemento = ''
    )
    {
        $this->setOrder($pedidoNumero, $pedidoValor, $pedidoDescricao);
        $this->setCustomer($compradorNome, $compradoCpfCnpj);
        $this->setCustomerAddress($compradorCep, $compradorUf, $compradorCidade, $compradorBairro, $compradorLogradouro, $compradorNumero, $compradorComplemento);
        $params = array(
            'merchant_id' => $this->merchantId,
            'meio_pagamento' => '300',
            'pedido' => $this->getOrder(),
            'comprador' => $this->getCustomer(),
            'boleto' => $this->getBillet(),
            'token_request_confirmacao_pagamento' => base64_encode($this->nossoNumero())
        );

        $data_post = json_encode($params);
        $url = '/apiboleto/transacao';
        return $this->sendCurl($url, $data_post);
    }

    /**
     * @return array
     */
    public function getOrder()
    {
        if (empty($this->order)) {
            throw new ShopFacilException('Faltam os dados do pedido.');
        }
        return $this->order;
    }

    /**
     * @param string $numero
     * @param double $valor
     * @param string $descricao
     * @return array
     * @throws ShopFacilException
     */
    public function setOrder($numero, $valor, $descricao = '')
    {
        $this->order = array(
            'numero' => $numero,
            'valor' => number_format($valor, 2, '', ''),
            'descricao' => $descricao
        );
        return $this->order;
    }

    /**
     * @return array
     */
    public function getCustomer()
    {
        if (
            empty($this->customer) or
            empty($this->customer['nome']) or
            empty($this->customer['documento'])
        ) {
            throw new ShopFacilException('Faltam os dados do comprador.');
        }
        $tmp = $this->customer;
        $tmp['endereco'] = $this->getCustomerAddress();
        return $tmp;
    }

    /**
     * @param string $nome
     * @param string $cpfCnpj
     * @param string $ip
     * @param string $userAgent
     * @return $this
     */
    public function setCustomer($nome, $cpfCnpj, $ip = '', $userAgent = '')
    {
        $this->customer = array(
            'nome' => $nome,
            'documento' => $this->onlyNumbers($cpfCnpj),
            'ip' => ($ip) ? $ip : $_SERVER['REMOTE_ADDR'],
            'user_agent' => ($userAgent) ? $userAgent : $_SERVER['HTTP_USER_AGENT']
        );
        return $this;
    }

    public function getCustomerAddress()
    {
        if (
            empty($this->customerAddress) or
            empty($this->customerAddress['cep']) or
            empty($this->customerAddress['logradouro']) or
            empty($this->customerAddress['cidade']) or
            empty($this->customerAddress['uf'])
        ) {
            throw new ShopFacilException('Faltam os dados do endereço do comprador.');
        }
        return $this->customerAddress;
    }

    /**
     * @param string $cep
     * @param string $uf
     * @param string $cidade
     * @param string $bairro
     * @param string $logradouro
     * @param string $numero
     * @param string $complemento
     * @return $this
     * @throws ShopFacilException
     */
    public function setCustomerAddress($cep, $uf, $cidade, $bairro, $logradouro, $numero, $complemento = '')
    {
        $this->customerAddress = array(
            'cep' => $this->onlyNumbers($cep),
            'logradouro' => $logradouro,
            'numero' => $numero,
            'complemento' => $complemento,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'uf' => $uf
        );
        return $this;
    }

    /**
     * @return array
     */
    public function getBillet()
    {
        if (empty($this->billet)) {
            $this->setBillet();
        }

        $tmp = $this->billet;
        $tmp['instrucoes'] = $this->getBilletInfo();
        return $tmp;
    }

    /**
     * @param string $dataVencimento
     * @param string $dataEmissao
     * @param array|null $registro
     * @return $this
     */
    public function setBillet($dataVencimento = '', $dataEmissao = '', $registro = null)
    {
        if (empty($dataEmissao)) {
            $dataEmissao = date('Y-m-d');
        }
        if (empty($dataVencimento)) {
            $dias = intval($this->conf['dias_vencimento_boleto']);
            $dataVencimento = date('Y-m-d', strtotime('+' . $dias . ' days'));
        }
        $nossoNumero = $this->nossoNumero();
        $this->billet = [
            'beneficiario' => $this->conf['beneficiario'],
            'carteira' => $this->conf['carteira'],
            'nosso_numero' => $nossoNumero,
            'data_emissao' => $dataEmissao,
            'data_vencimento' => $dataVencimento,
            'valor_titulo' => $this->getOrder()['valor'],
            'url_logotipo' => $this->conf['url_logotipo'],
            'mensagem_cabecalho' => $this->conf['mensagem_cabecalho'],
            'tipo_renderizacao' => $this->conf['tipo_renderizacao'],
            'registro' => $registro
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getBilletInfo()
    {
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($this->billetInfo['instrucao_linha_' . $i])) {
                $this->billetInfo['instrucao_linha_' . $i] = '';
            }
        }
        return $this->billetInfo;
    }

    /**
     * @param array|string $info
     * @param int $line
     * @return Shopfacil
     */
    public function setBilletInfo($info, $line = 0)
    {
        if (!is_array($info)) {
            if (!$line) {
                $line = count($this->billetInfo) + 1;
            }
            $this->billetInfo['instrucao_linha_' . ($line - 1)] = $info;
        } else {
            $this->billetInfo = $info;
        }
        return $this;
    }

    /**
     * @param int $numeroParcela
     * @return false|string
     * @throws ShopFacilException
     */
    private function nossoNumero($numeroParcela = 1)
    {
        $numeroParcela = $this->zeroDireita($numeroParcela, 3);
        $numeroPedido = $this->zeroDireita($this->getOrder()['numero'], 8);
        return substr((string)$numeroPedido . $numeroParcela, -11);
    }

    /**
     * @param string $valor
     * @param int $quantidade
     * @return string
     */
    private function zeroDireita($valor, $quantidade)
    {
        return str_pad($valor, $quantidade, '0', STR_PAD_LEFT);
    }

    /**
     * @param string $uri
     * @param array|null $params
     * @param bool $autorizacaoPorEmail
     * @return object
     */
    private function sendCurl($uri, $params = null, $autorizacaoPorEmail = false)
    {
        $url = (($this->sandbox) ? self::URL_SANDBOX : self::URL_PROD) . $uri;
        //Configuracao do cabecalho da requisicao
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Charset: UTF-8';
        $headers[] = 'Accept-Encoding: application/json';
        $headers[] = 'Content-Type: application/json;charset=UTF-8';

        $AuthorizationHeader = (($autorizacaoPorEmail) ? $this->merchantEmail : $this->merchantId) . ':' . $this->merchantKey;
        $AuthorizationHeaderBase64 = base64_encode($AuthorizationHeader);
        $headers[] = 'Authorization: Basic ' . $AuthorizationHeaderBase64;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($params) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        return json_decode($result);
    }

    /**
     * @param $numeroPedido
     * @return mixed
     */
    public function serviceGetOrderById($numeroPedido)
    {
        $url = '/SPSConsulta/GetOrderById/' . $this->merchantId . '?token=' . $this->getToken() . '&orderId=' . $numeroPedido;
        return $this->sendCurl($url, null, true);
    }

    /**
     * @return string
     */
    public function getToken()
    {

        if ($this->token) {
            return $this->token;
        }

        $auth = $this->serviceAuthorization();
        if ($auth && ($token = $auth->token->token)) {
            $this->token = $auth->token->token;
        }

        return $this->token;
    }

    /**
     * @return object
     */
    public function serviceAuthorization()
    {
        $url = '/SPSConsulta/Authentication/' . $this->merchantId;
        return $this->sendCurl($url, null, true);
    }

    /**
     * @param string $dateInitial data formato aaaa/mm/dd
     * @param string $dateFinal data formato aaaa/mm/dd periodo maximo 6 dias
     * @param int $status 0 (Todos os pedidos) ou 1 (Pedidos pagos).
     * @param int $offset maior que 1
     * @param int $limit maximo 1500
     * @return object
     */
    public function serviceGetOrderListPayment($dateInitial = '', $dateFinal = '', $status = 0, $offset = 1, $limit = 10)
    {
        if (empty($dateInitial)) {
            $dateInitial = date('Y/m/d', strtotime('-5 days'));
        }
        if (empty($dateFinal)) {
            $dateFinal = date('Y/m/d', strtotime('+1 days'));
        }
        $url = '/SPSConsulta/GetOrderListPayment/' . $this->merchantId . '/boleto?token=' . $this->getToken() . '&dataInicial=' . $dateInitial . '&dataFinal=' . $dateFinal . '&status=' . $status . '&offset=' . $offset . '&limit=' . $limit;
        return $this->sendCurl($url, null, true);
    }

    /**
     * @param $valor
     * @return string|string[]|null
     */
    private function onlyNumbers($valor)
    {
        return preg_replace('/\D/', '', $valor);
    }
}
