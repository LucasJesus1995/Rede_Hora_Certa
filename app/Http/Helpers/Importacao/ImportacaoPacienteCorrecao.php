<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 2019-05-03
 * Time: 16:57
 */

namespace App\Http\Helpers\Importacao;


use App\Http\Helpers\Util;
use App\Importacao;
use App\ImportacaoAgenda;
use App\Pacientes;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class ImportacaoPacienteCorrecao
{

    public static function import(Array $_paciente, ImportacaoAgenda $importacao, $linha)
    {
        $data = [];

        $cns = (strlen(Util::somenteNumeros($_paciente[1])) > 11) ? Util::somenteNumeros($_paciente[1]) : null;
        if (is_null($cns)) {
            throw new \Exception("CNS '{$cns}' inválido!");
        }

        $paciente = Pacientes::getByCNS($cns);
        if (!empty($paciente->id)) {
            $data['id'] = $paciente->id;
        }

        $data['cns'] = $cns;
        $data['nome'] = trim($_paciente[0]);
        $data['mae'] = trim($_paciente[2]);
        $data['nascimento'] = $_paciente[3];
        $data['sexo'] = Importacao::getSexo($_paciente[4]);
        $data['nacionalidade'] = Importacao::getNacionalidade($_paciente[5]);
        $data['raca_cor'] = Importacao::getRacaoCor($_paciente[6]);
        $data['celular'] = Util::somenteNumeros($_paciente[10]);
        $data['telefone_residencial'] = Util::somenteNumeros($_paciente[11]);
        $data['telefone_comercial'] = Util::somenteNumeros($_paciente[12]);
        $data['email'] = trim($_paciente[10]);
        $data['contato'] = trim($_paciente[11]);
        $data['cep'] = Util::somenteNumeros($_paciente[12]);
        $data['cidade'] = (new \App\Importacao)->getCidade($_paciente[13]);
        $data['endereco_tipo'] = Importacao::getMunicipio($_paciente[14]);
        $data['endereco'] = trim($_paciente[15]);
        $data['numero'] = trim($_paciente[16]);
        $data['bairro'] = trim($_paciente[17]);
        $data['complemento'] = trim($_paciente[18]);
        $data['cpf'] = trim($_paciente[19]);
        $data['rg'] = trim($_paciente[20]);
        $data['import'] = $importacao->id . '-' . $linha;

        $__paciente = new Pacientes();
        $__paciente->saveData($data);

        return Pacientes::getByCNSCompleto($cns);
    }


}