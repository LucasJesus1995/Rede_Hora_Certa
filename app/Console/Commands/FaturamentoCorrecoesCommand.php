<?php

namespace App\Console\Commands;

use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\FaturamentoProcedimento;
use Illuminate\Console\Command;

class FaturamentoCorrecoesCommand extends Command
{
    protected $signature = 'cies:faturamento-correcoes';
    protected $description = 'Corrige os procedimentos dentro do atendimento';

    public function handle()
    {
        //$this->procedimentoCirurgiaVascular();
    }

    private function procedimentoCirurgiaVascular()
    {

        $atendimento_procedimentos = AtendimentoProcedimentos::select("atendimento_procedimentos.*")
            ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('faturamento_procedimentos', 'faturamento_procedimentos.atendimento_procedimento', '=', 'atendimento_procedimentos.id')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->where('faturamento_procedimentos.faturamento', 25)
            ->where('atendimento_procedimentos.procedimento', 12)
            ->where('agendas.linha_cuidado', 46)
            ->get();


        exit("<pre>" . print_r($atendimento_procedimentos->count(), true) . "</pre>");

        foreach ($atendimento_procedimentos AS $atendimento_procedimento) {
            echo "\n " . $atendimento_procedimento->atendimento;

            $_atendimento_procedimento = $atendimento_procedimento->replicate();
            $_atendimento_procedimento->procedimento = 107;
            $_atendimento_procedimento->faturado = 0;
            $_atendimento_procedimento->quantidade = 1;
            $_atendimento_procedimento->multiplicador = 2;
            $_atendimento_procedimento->autorizacao = null;
            $_atendimento_procedimento->save();
        }

    }


}
