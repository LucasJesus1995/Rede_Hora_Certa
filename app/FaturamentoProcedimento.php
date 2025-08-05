<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaturamentoProcedimento extends Model
{
    protected $table = 'faturamento_procedimentos';

    public static function getByParams($arena, $linha_cuidado, $procedimento, $ano, $mes){
        $_res =  FaturamentoProcedimento::select(
            [
                'faturamento_procedimentos.id',
                'faturamento_procedimentos.valor'
            ]
        )
            ->where('faturamento_procedimentos.arena', $arena)
            ->where('faturamento_procedimentos.linha_cuidado', $linha_cuidado)
            ->where('faturamento_procedimentos.procedimento', $procedimento)
            ->where('faturamento_procedimentos.ano', $ano)
            ->where('faturamento_procedimentos.mes', $mes)
            ->where('faturamento_procedimentos.status',  '=', 1)
            ->get()
            ->toArray()
        ;

        return !empty($_res[0]) ? $_res[0] : null;
    }

    public static function getFaturamentoQuantidade($lote, $linha_cuidado, $faturamento){
            $_res =  FaturamentoProcedimento::select(
                \DB::raw('SUM(faturamento_procedimentos.quantidade) AS quantidade')
            )
            ->where('lote', $lote)
            ->where('faturamento', $faturamento)
            ->where('status',  1)
            ->where('linha_cuidado', $linha_cuidado)
            ->get()
            ->toArray()
        ;

        return !empty($_res[0]['quantidade']) ? $_res[0]['quantidade'] :  0;
    }

    public static function getFaturamentoProcedimentoByAtendimento($atendimento){

        $res = FaturamentoProcedimento::select(
            [
                'faturamento_procedimentos.*',
            ]
        )
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
            ->where('atendimento_procedimentos.atendimento', $atendimento)
            ->get();

        return !empty($res) ? $res : [];
    }

}
