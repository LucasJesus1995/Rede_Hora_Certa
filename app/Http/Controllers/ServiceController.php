<?php

namespace App\Http\Controllers;


use App\Agendas;
use App\Atendimentos;
use App\Cidades;
use App\Http\Helpers\AtendimentoHelpers;
use App\Http\Helpers\Util;
use App\Pacientes;
use App\Services\SIGA\PacientePesquisar;
use App\Tipos;
use FlyingLuscas\ViaCEP\ZipCode;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Zend\Filter\Digits;

class ServiceController extends Controller
{

    public function getCNS($cns)
    {
        $paciente = Pacientes::getPacienteByCNS($cns);

        return !empty($paciente) ? $paciente->toArray() : [];
    }

    public function getCEP($cep)
    {
        $digits = new Digits();

        $cep = $digits->filter($cep);
        $cep = Util::StrPadLeft($cep, 8);

        return Util::getCEP($cep);
    }

    public function getSigaPacientePesquisarByCNS($cns)
    {
        try {
            $paciente = (new \App\Services\SIGA\PacientePesquisar)->pesquisar($cns);

            exit("<pre>" . print_r($paciente, true) . "</pre>");

        } catch (\Exception $e) {
            exit("<pre>" . print_r($e, true) . "</pre>");
        }

        exit("<pre>" . print_r($cns, true) . "</pre>");
    }

    public function nossosNumeros()
    {
        return Agendas::nossosNumeros();
    }

    public function getCondutasTipoAtendimento(Request $request)
    {
        $response['status'] = false;
        try {
            $atendimento = Atendimentos::get($request->get('atendimento'));
            $agenda = Agendas::get($atendimento['agenda']);

            $geral = AtendimentoHelpers::getCondutasEspecialidadeTipoAtendimento($agenda['linha_cuidado'], $request->get('tipo_atendimento'), 0);
            $regulacao = AtendimentoHelpers::getCondutasEspecialidadeTipoAtendimento($agenda['linha_cuidado'], $request->get('tipo_atendimento'), 1);

            $response['status'] = true;
            $response['data']['geral'] = $geral;
            $response['data']['regulacao'] = $regulacao;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

}