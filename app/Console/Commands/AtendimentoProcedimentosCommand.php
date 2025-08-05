<?php

namespace App\Console\Commands;

use App\Agendas;
use App\Arenas;
use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\AtendimentoStatus;
use App\Faturamento;
use App\FaturamentoLotes;
use App\FaturamentoProcedimento;
use App\Http\Helpers\Util;
use App\LinhaCuidadoProcedimentos;
use App\Lotes;
use App\LotesArena;
use App\LotesLinhaCuidado;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Logger\ConsoleLogger;

class AtendimentoProcedimentosCommand extends Command
{
    protected $signature = 'cies:atendiemnto-procedimentos';
    protected $description = 'Ajuda os procedimentos conforme necessarios';

    private $_faturamento = '16';
    private $_lote = '7';

    public function __construct()
    {
        ini_set('memory_limit', -1);

        parent::__construct();
    }

    public function handle()
    {
        // Ira fatura 40% dos procedimentos de ultrassom
        //$this->_procedimentosUltrassomConsulta();
        $this->_procedimentosAnestesia();
    }

    /**
     * Remove todas as consulta do faturamento e do atendiemnto referente ao lote
     */
    private function _procedimentosUltrassomConsulta()
    {
        $sql = "SELECT
              faturamento_procedimentos.*
            FROM
              faturamento_procedimentos
              JOIN atendimento_procedimentos ON atendimento_procedimentos.id = faturamento_procedimentos.atendimento_procedimento
              JOIN atendimento ON atendimento.id = atendimento_procedimentos.atendimento
              JOIN agendas ON agendas.id = atendimento.agenda
            WHERE
              faturamento_procedimentos.faturamento >= {$this->_faturamento}
              AND faturamento_procedimentos.lote IN ({$this->_lote})
              AND atendimento_procedimentos.procedimento IN (11)
              AND agendas.linha_cuidado IN (7)
            LIMIT 10000";

        $faturamento_procedimentos = DB::select($sql);
        if(count($faturamento_procedimentos)) {
            foreach ($faturamento_procedimentos AS $faturamento_procedimento) {
                $_faturamento_procedimento = FaturamentoProcedimento::find($faturamento_procedimento->id);
                $_atendimento_procedimento = AtendimentoProcedimentos::find($faturamento_procedimento->atendimento_procedimento);

//                $_faturamento_procedimento->delete();
//                $_atendimento_procedimento->delete();
            }
        }

        $sql = "SELECT
              atendimento_procedimentos.*
            FROM
              atendimento_procedimentos
              JOIN atendimento ON atendimento.id = atendimento_procedimentos.atendimento
              JOIN agendas ON agendas.id = atendimento.agenda
            WHERE
              atendimento_procedimentos.procedimento IN (11)
              AND agendas.linha_cuidado IN (7)
              AND agendas.data >= '2018-05-01 00:00:00'
            LIMIT 10000";

        $atendimento_procedimentos = DB::select($sql);
        if(count($atendimento_procedimentos)) {
            foreach ($atendimento_procedimentos AS $atendimento_procedimento) {
                $_atendimento_procedimento = AtendimentoProcedimentos::find($atendimento_procedimento->id);

                $_atendimento_procedimento->delete();
            }
        }

    }

    private function _procedimentosAnestesia()
    {
        $sql = "SELECT
              faturamento_procedimentos.*
            FROM
              faturamento_procedimentos
              JOIN atendimento_procedimentos ON atendimento_procedimentos.id = faturamento_procedimentos.atendimento_procedimento
              JOIN atendimento ON atendimento.id = atendimento_procedimentos.atendimento
            WHERE
              faturamento_procedimentos.faturamento >= {$this->_faturamento}
              AND faturamento_procedimentos.lote IN ({$this->_lote})
              AND atendimento_procedimentos.procedimento IN (27)
            LIMIT 500";

        $faturamento_procedimentos = DB::select($sql);
        if(count($faturamento_procedimentos)) {
            foreach ($faturamento_procedimentos AS $faturamento_procedimento) {
                $_faturamento_procedimento = FaturamentoProcedimento::find($faturamento_procedimento->id);
                $_atendimento_procedimento = AtendimentoProcedimentos::find($faturamento_procedimento->atendimento_procedimento);

                $_atendimento_procedimento->faturado = 0;
                $_atendimento_procedimento->save();

                $_faturamento_procedimento->delete();
            }
        }
    }
}
