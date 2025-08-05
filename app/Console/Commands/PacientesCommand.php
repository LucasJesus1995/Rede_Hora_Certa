<?php

namespace App\Console\Commands;

use App\Agendas;
use App\Http\Helpers\SIGAHelpers;
use App\Pacientes;
use App\Services\SIGA\PacientePesquisar;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PacientesCommand extends Command
{

    protected $signature = 'cies:pacientes';

    protected $description = 'Executa algumas rotinas referente ao paciente dentro do sistema';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->getPacienteRemarcacao();
    }

    private function getPacienteRemarcacao()
    {
        $date = Carbon::createFromTime("0","0","0")->subMonth(24);

        $pacientes = Pacientes::whereNotNull('cns')
            ->where(function ($query) {
                $query->orWhere('nome', '=', '')
                    ->orWhere('sexo', '=', '')
                    ->orWhere('nascimento', '=', '')
                    ->orWhere('nascimento', '=', null)
                ;
            })
            ->where('created_at', '>=', $date->toDateTimeString())
            ->orderBy('id', 'desc')
            ->limit(2000)
            ->get();

        if (!empty($pacientes)) {
            $pacientePequisar = new PacientePesquisar();
            foreach ($pacientes AS $paciente) {
                try {
                    $paciente_siga = $pacientePequisar->pesquisar($paciente->cns);


                    if (!empty($paciente_siga)) {
                        $paciente = SIGAHelpers::populatePaciente($paciente, $paciente_siga);

                        $paciente->save();
                        echo ".";
                    }else {
                        echo "-";
                    }
                } catch (\Exception $exception) {
//                    exit("<pre>" . print_r($exception->getMessage(), true) . "</pre>");
                }
            }

            $this->call('cache:clear');
        }
    }

}


