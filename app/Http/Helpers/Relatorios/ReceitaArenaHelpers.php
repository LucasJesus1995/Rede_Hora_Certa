<?php

namespace App\Http\Helpers\Relatorios;


use App\Procedimentos;
use Illuminate\Support\Facades\DB;

class ReceitaArenaHelpers
{
    
    public static function getReceitaArenas($contrato, $faturamento, $arena = null, $linha_cuidado = null)
    {
        $sql =  Procedimentos::select(
                [
                    'arenas.id',
                    'arenas.nome',
                    DB::raw('SUM((atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) * contrato_procedimentos.valor_unitario) AS receita')
                ]
            )
            ->join('linha_cuidado_procedimentos','linha_cuidado_procedimentos.procedimento',  '=', 'procedimentos.id')
            ->join('linha_cuidado','linha_cuidado_procedimentos.linha_cuidado',  '=', 'linha_cuidado.id')
            ->join('atendimento_procedimentos','atendimento_procedimentos.procedimento',  '=', 'procedimentos.id')
            ->join('atendimento','atendimento_procedimentos.atendimento',  '=', 'atendimento.id')
            ->join('agendas','atendimento.agenda',  '=', 'agendas.id')
            ->join('arenas','arenas.id',  '=', 'agendas.arena')
            ->join('contrato_procedimentos','contrato_procedimentos.procedimento',  '=', 'procedimentos.id')
            ->where('contrato_procedimentos.lote', $contrato)
            ->orderBy('receita','desc');

        if(is_null($arena) && is_null($linha_cuidado)){
            $sql->groupBy(
                [
                    'arenas.id',
                    'arenas.nome'
                ]
            );
        }

        $sql->join('faturamento_procedimentos','faturamento_procedimentos.atendimento_procedimento',  '=', 'atendimento_procedimentos.id');
        $sql->where('faturamento_procedimentos.status',  '=', 1);
        $sql->where('faturamento_procedimentos.faturamento', $faturamento);

        if($arena){
            $sql->select(
                [
                    'linha_cuidado.id',
                    'linha_cuidado.nome',
                    DB::raw('SUM((atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) * contrato_procedimentos.valor_unitario) AS receita')
                ]
            )->groupBy(
                    [
                        'linha_cuidado.id',
                        'linha_cuidado.nome',
                    ]
                )
            ->where('arenas.id', $arena);
        }

        if($linha_cuidado){
            $sql->select(
                [
                    'procedimentos.id',
                    'procedimentos.nome',
                    DB::raw('SUM((atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) * contrato_procedimentos.valor_unitario) AS receita')
                ]
            )->groupBy(
                    [
                        'procedimentos.id',
                        'procedimentos.nome',
                    ]
                )
            ->where('arenas.id', $arena)
            ->where('linha_cuidado.id', $linha_cuidado);
//            //echo $sql->toSql();
//            exit("<pre>" . print_r($sql->get()->toArray(), true) . "</pre>");
//            die;
//

        }




        return $sql;
    }
}