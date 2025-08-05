<?php

namespace App\Services\SIGA;

use SoapFault;

class PacientePesquisar extends AbstractSIGA
{
    protected $cns;

    public function __construct()
    {
//        $this->url = config("siga.url");
        $this->url = "http://ws.siga.saude.prefeitura.sp.gov.br:8080/smsservices/services/";

        $this->wsdl = $this->url . "PessoaService?wsdl";
        $this->location = $this->url . "PessoaService.PessoaServiceHttpSoap12Endpoint";

        $this->__init();
    }

    public function pesquisar($cns)
    {
        $this->cns = $cns;

        try {
            //$cns = "801440494604749"
            return $this->_getPaciente($cns);

        } catch (SoapFault $fault) {
            exit("<pre>" . print_r($fault->faultstring, true) . "</pre>");

            print("faultstring: " . print_r($fault->faultstring, true));
            print("getMessage: " . print_r($fault->getMessage(), true));
            print("__getLastRequest: " . print_r($this->client->__getLastRequest(), true));
            print("__getLastResponse: " . print_r($this->client->__getLastResponse(), true));
        }
    }

    protected function _getPaciente($cns)
    {
        $params['numeroCns'] = $cns;
        $this->client->pesquisar($params);

        return $this->getResponseArray();
    }

}