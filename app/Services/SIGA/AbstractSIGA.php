<?php

namespace App\Services\SIGA;

use SoapClient;
use SoapHeader;
use SoapVar;

abstract class AbstractSIGA
{
    protected $client;
    protected $sistema;
    protected $senha;
    protected $login;
    protected $location;
    protected $wsdl;
    protected $options;

    public function __init()
    {
        $this->login = "CIES";
        $this->senha = "CIES!@#$";
        $this->sistema = "CIES";

        $this->options = array(
            "location" => $this->location,
            'trace' => true,
            'exceptions' => false,
            'soap_version' => 2,
            'wsdl_cache' => 0,
            'cache_wsdl' => 0,
            'connection_timeout' => 15,
            'encoding' => 'UTF-8',
        );

        $this->client = new SoapClient(
            $this->wsdl,
            $this->options
        );

        $auth = "<ns2:login>" . $this->login . "</ns2:login><ns2:password>" . $this->senha . "</ns2:password><ns2:sistema>" . $this->sistema . "</ns2:sistema>";

        $varHeader = new SoapVar($auth, XSD_ANYXML, null, null, null);

        $this->client->__setSoapHeaders(new SoapHeader("http://auth.smssp.atech.br", "ns2", $varHeader, false));

    }

    public function getResponse()
    {
        $data = explode("\n", $this->client->__getLastResponse());

        return !empty($data[5]) ? $data[5] : null;
    }

    public function _retorno($data)
    {
        return $data[0];
    }

    protected function getResponseArray()
    {
        $data = [];

        $response = $this->getResponse();

        if (!is_null($response)) {
            $xml = xml_parser_create();
            xml_parse_into_struct($xml, $response, $data);
            xml_parser_free($xml);

            $data = $this->_clearResponse($data);
        }

        return $data;
    }

    protected function _clearResponse(array $data, $_tag = ["AX23", "AX21"])
    {
        $_data = [];
        foreach ($data as $row) {
            $tag = !empty($row['tag']) ? explode(":", $row['tag'])[0] : null;

            if (in_array($tag, $_tag)) {
                $key = strtolower(str_replace($tag . ":", "", $row['tag']));

                $_data[$key] = $this->getValues($row);
            }
        }

        return $_data;
    }

    protected function getValues($data)
    {
        $value = null;

        if (!empty($data['value'])) {
            $value = $data['value'];
        }

        if (is_null($value) && !empty($data['attributes'])) {
            $value = current(array_values($data['attributes']));
        }

        return $value;
    }
}