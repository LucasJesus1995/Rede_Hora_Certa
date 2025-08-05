<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 30/01/19
 * Time: 14:36
 */

namespace App\Http\Helpers\Importacao;


use App\Agendas;
use App\Pacientes;
use Illuminate\Support\Facades\DB;

abstract class AbstractImportacao
{
    public $_error = false;
    private $logger = array();

    public function __construct()
    {
        $this->params = [];
        $this->logger = null;

        $this->records = 0;
        $this->imported = 0;
        $this->failure = 0;
    }

    public function setLog($message = null)
    {
        if (is_null($message)) {
            $this->logger[] = null;
        } else {
            $this->logger[] = date('H:i:s') . ' - ' . $message;
        }
    }

    public function setLogFail($message, $color = false)
    {
        if (!$color) {
            $this->logger[] = date('H:i:s') . ' - ' . $message;
        } else {
            $this->logger[] = "<b style='color: #f00'>" . date('H:i:s') . ' - ' . $message . "</b>";
        }
    }

    public function getLog()
    {
        return $this->logger;
    }


    protected function getPaciente($cns, $nascimento, $nome = null)
    {
        $paciente = Pacientes::getPacienteByCNS($cns);

        if (empty($paciente->id)) {
            if (!is_null($nome)) {
                $paciente = new Pacientes();
                $paciente->cns = $cns;
                $paciente->nascimento = $nascimento;
                $paciente->nome = $nome;
                $paciente->save();

                $this->setLog("Paciente foi cadastro no sistema!");
            } else {
                throw new \Exception("Não foi possivel cadastrar o paciente!", true);
            }

        } else {
            $this->setLog("Paciente cadastrado no sistema!");
        }

        return $paciente->id;
    }

    protected function getPacienteAgendado($paciente, $agendamento)
    {
        $agenda = Agendas::where('paciente', $paciente)->where('data', '=', $agendamento)->get();

        if (!empty($agenda[0])) {
            throw new \Exception("Paciente encontra-se agendado neste dia!");
        }

        return true;
    }

}