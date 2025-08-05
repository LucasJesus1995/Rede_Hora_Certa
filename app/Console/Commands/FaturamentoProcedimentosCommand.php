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

class FaturamentoProcedimentosCommand extends Command
{
    protected $signature = 'cies:faturamento-procedimentos';
    protected $description = 'Insere os procedimentos obrigatorios, caso não seja inserido pelo digitador';

    private $_inicio_faturamentos_procedimentos = '2017-10-01 00:00:00';
    private $_limit = 200;

    public function __construct()
    {
        ini_set('memory_limit', -1);

        parent::__construct();
    }

    public function handle()
    {
        $this->_sedacaoAndColoEda();
    }

    private function _sedacaoAndColoEda()
    {
        $start = $this->_inicio_faturamentos_procedimentos;

        $sql = Atendimentos::select(
                [
                 'atendimento.id'
                ]
            )
            ->join('agendas','agendas.id','=','atendimento.agenda')
            ->where('agendas.data','>=', $start)
            ->whereIn('agendas.linha_cuidado', [1,2])
            ->whereIn('atendimento.status', [6,10,98])
            ->whereNotIn('atendimento.id', function($query) use ($start){
                $query->select('atendimento_procedimentos.atendimento')
                    ->from('atendimento_procedimentos')
                    ->where('atendimento_procedimentos.created_at', '>=', $start)
                    ->where('atendimento_procedimentos.procedimento','=', 3)
                    ;
            })
            ->limit($this->_limit)
            ->orderBy('agendas.id', 'asc')
            ->get()
        ;

        if($sql->count()){
            foreach ($sql AS $row){
                $atendimento_procedimentos = AtendimentoProcedimentos::select('*')->where('atendimento_procedimentos.atendimento', $row->id)->get();

                if($atendimento_procedimentos->count()) {
                    $atendimento_procedimento = $atendimento_procedimentos[0]->replicate();
                    $atendimento_procedimento->user = 1;
                    $atendimento_procedimento->procedimento = 3;
                    $atendimento_procedimento->finalizacao = null;
                    $atendimento_procedimento->faturado = 0;
                    $atendimento_procedimento->save();
                }
            }
        }
    }

}
