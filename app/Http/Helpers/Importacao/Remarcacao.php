<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 30/01/19
 * Time: 14:36
 */

namespace App\Http\Helpers\Importacao;


use App\Agendas;
use App\ImportacaoAgenda;
use Illuminate\Support\Facades\Auth;

class Remarcacao extends AbstractImportacao
{
    private $data = null;


    public function __construct(Array $data)
    {
        parent::__construct();

        $this->data = $data;
    }

    public function process()
    {

        if (!empty($this->data) && is_array($this->data)) {
            $this->records = count($this->data);

            $importacao_agenda = new ImportacaoAgenda();
            $importacao_agenda->data = serialize($this->params);
            $importacao_agenda->records = count($this->data);
            $importacao_agenda->tipo = 1;
            $importacao_agenda->file = null;
            $importacao_agenda->user = Auth::user()->id;
            $importacao_agenda->save();
            
            $ln = 1;
            foreach ($this->data AS $row) {

                try {
                    $agendamento = $row['data'] . " " . $row['horario'];

                    $this->setLog("Inicio do cadastro '{$row['cns']}' ");

                    $paciente = $this->getPaciente($row['cns'], $row['nascimento'], $row['nome']);
                    $this->getPacienteAgendado($paciente, $agendamento);

                    $agenda = new Agendas();
                    $agenda->paciente = $paciente;
                    $agenda->arena = $row['arena'];
                    $agenda->linha_cuidado = $row['linha_cuidado'];
                    $agenda->data = $agendamento;
                    $agenda->import = $importacao_agenda->id . "-" . ($ln++);

                    $this->setLog("Dados a serem importado: " . json_encode($agenda->toArray()));
                    $agenda->save();

                    $this->setLog("Paciente agendado com sucesso!");
                    $this->setLog();

                    $this->imported++;
                } catch (\Exception $e) {
                    $this->failure++;
                    $this->setLogFail($e->getMessage(), true);
                    $this->setLogFail(json_encode($row), true);
                    $this->setLog();
                    $this->setLog();
                }
            }

            $importacao_agenda->imported = $this->imported;
            $importacao_agenda->failure = $this->failure;
            $importacao_agenda->log = serialize($this->getLog());
            $importacao_agenda->save();


        }

        return true;
    }


}