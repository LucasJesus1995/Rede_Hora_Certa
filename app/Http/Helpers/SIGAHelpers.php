<?php

namespace App\Http\Helpers;


use App\CEPs;
use App\Cidades;
use App\Pacientes;
use Carbon\Carbon;
use Zend\Filter\Digits;

class SIGAHelpers
{

    public static function populatePaciente(Pacientes $paciente, Array $data)
    {
        $digits = new Digits();

        $paciente->nome = $data['nome'];

        if (!empty($data['datanascimento'])) {
            $data_nascimento = Carbon::createFromFormat("Y-m-d", current(explode("T", $data['datanascimento'])));

            if (!empty($data_nascimento)) {
                $paciente->nascimento = $data_nascimento->format("Y-m-d");
            }
        }

        if (!empty($digits->filter($data['codigomunicipioresidenciasus']))) {
            $cidade = Cidades::getByIbgeLike($data['codigomunicipioresidenciasus']);
            if (!empty($cidade->id)) {
                $paciente->cidade = $cidade->id;
            }
        }

        if (!empty($data['codigomunicipionascimentosus'])) {
            $cidade_nascimento = Cidades::getByIbgeLike($data['codigomunicipionascimentosus']);

            if (!empty($cidade_nascimento->id)) {
                $paciente->nascimento_municipio = $cidade_nascimento->nome;
                $paciente->nascimento_estado = $cidade_nascimento->estado;
            }
        }

        if (!empty($data['nomemae'])) {
            $paciente->mae = $data['nomemae'];
        }

        if (!empty($data['cpf'])) {
            $paciente->cpf = $data['cpf'];
        }

        if (!empty($data['codigosexosus'])) {
            $paciente->sexo = ($data['codigosexosus'] == "F") ? 2 : 1;
        }

        if (!empty($data['codigoracasus'])) {
            $paciente->raca_cor = $data['codigoracasus'];
        }

        if (!empty($data['telefonecelular'])) {
            $paciente->celular = $data['dddcelular'] . $data['telefonecelular'];
        }

        if (!empty($data['telefoneresidencial'])) {
            $paciente->telefone_residencial = $data['dddresidencial'] . $data['telefoneresidencial'];
        }

        if (!empty($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $paciente->email = $data['email'];
        }

        if (!empty($data['cep'])) {
            $paciente->cep = $data['cep'];
        }

        if (!empty($data['endereco'])) {
            $paciente->endereco = $data['endereco'];
        }

        if (!empty($data['numeroresidencia'])) {
            $paciente->numero = $data['numeroresidencia'];
        }

        if (!empty($data['bairro'])) {
            $paciente->bairro = $data['bairro'];
        }

        if (!empty($data['contato'])) {
            $paciente->contato = $data['contato'];
        }

        if (empty($data['endereco']) && !empty($data['cep'])) {
            $cep = $digits->filter($data['cep']);
            //$cep = Util::getCEP(Util::StrPadLeft($cep, 8));
            $cep = CEPs::getByCEP(Util::StrPadLeft($cep, 8));

            $paciente->endereco = Util::String2DB($cep['logradouro']);
        }

        if (empty($paciente->estabelecimento)) {
            unset($paciente->estabelecimento);
        }

        return $paciente;
    }
}