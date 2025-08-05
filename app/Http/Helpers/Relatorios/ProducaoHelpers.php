<?php

namespace App\Http\Helpers\Relatorios;

use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\DB;

class ProducaoHelpers
{

    public static function getProducaoMedicos($ano, $mes, $arena = null, $medico = null)
    {
        $data = Util::periodoMesPorAnoMes($ano, $mes);

        $sql = AtendimentoProcedimentos::select(

            [
                DB::raw('DATE_FORMAT(agendas.data,"%d") AS dia'),
                'atendimento.id AS codigo_atendimento',
                'arenas.nome AS arena',
                'agendas.tipo_atendimento',
                'linha_cuidado.nome AS linha_cuidado',
                'profissionais.id AS codigo_medico',
                'profissionais.nome AS medico',
                'procedimentos.sus AS codigo_procedimento',
                'procedimentos.nome AS procedimento',
                'procedimentos.id AS procedimento_id',
                DB::raw('SUM(atendimento_procedimentos.quantidade) AS quantidade')
            ]
        )
            ->join('faturamento_procedimentos', 'atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
            ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('profissionais', 'profissionais.id', '=', 'atendimento.medico')
            ->whereBetween('agendas.data', [$data['start'], $data['end']])
            ->orderBy('dia', 'ASC')
            ->orderBy('arenas.nome', 'ASC')
            ->orderBy('linha_cuidado.nome', 'ASC')
            ->orderBy('profissionais.nome', 'ASC')
            ->groupBy('dia', 'arena', 'linha_cuidado', 'medico', 'procedimento', 'procedimento_id')
            //->limit(10)
        ;

        if (!empty($arena)) {
             $sql->where("arenas.id", $arena);
        }

        if (!empty($medico)) {
            $sql->where("profissionais.id", $medico);
        }

        return $sql->get();
    }

}