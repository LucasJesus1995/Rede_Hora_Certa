<?php

namespace App\Console\Commands;


use App\AtendimentoProcedimentos;
use Illuminate\Console\Command;

class FaturamentoProcedimentosObrigatoriosCommand extends Command
{
    protected $signature = 'cies:faturamento-procedimentos-obrigatorios';
    protected $description = 'Ajusta os atendimento referente aos procedimentos obrigatorios';

    private $_limit = 100;

    public function __construct()
    {
        ini_set('memory_limit', -1);

        parent::__construct();
    }

    public function handle()
    {
        $this->pesquisaHelicobter();
    }

    private function pesquisaHelicobter()
    {
        $sql = AtendimentoProcedimentos::select(
                [
                    'atendimento_procedimentos.*'
                ]
            )
            ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->whereIn('atendimento_procedimentos.procedimento', [2])
            ->whereIn('atendimento.status', [6, 10])
            ->whereNotIn('atendimento_procedimentos.atendimento', function ($query) {
                $query->select('atendimento_procedimentos.atendimento')
                    ->from('atendimento_procedimentos')
                    ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
                    ->whereIn('atendimento.status', [6, 10])
                    ->whereIn('atendimento_procedimentos.procedimento', [4]);
            })
            ->orderBy('atendimento.id', 'desc')
            ->limit($this->_limit)
            ->get();

        if (!empty($sql)) {
            foreach ($sql AS $row){
                $atendimento_procedimentos = AtendimentoProcedimentos::find($row->id);
                
                if(!empty($atendimento_procedimentos)){
                    $_atendimento_procedimento = $atendimento_procedimentos->replicate();
                    $_atendimento_procedimento->user = 1;
                    $_atendimento_procedimento->procedimento = 4;
                    $_atendimento_procedimento->finalizacao = null;
                    $_atendimento_procedimento->faturado = 0;
                    $_atendimento_procedimento->save();
                }

            }
        }
    }

}
