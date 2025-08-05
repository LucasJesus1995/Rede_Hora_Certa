<?php

namespace App\Http\Helpers\Relatorios;

use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\DB;

class EstatisticasHelpers
{

    public static function getGenero($ano, $mes, $arena = null, $tipo = null)
    {
        $data = Util::periodoMesPorAnoMes($ano, $mes);

        $status = ($tipo == 1) ? [98, 99] : [2, 6, 10, 98, 99];

        $sql = Atendimentos::select(

            [
                'linha_cuidado.nome AS abreviacao',
                'profissionais.nome',
                'pacientes.sexo',
                DB::raw('DATE_FORMAT(agendas.data,"%d") AS data_agendamento'),
                DB::raw('COUNT(1) AS total')
            ]
        )
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('profissionais', 'profissionais.id', '=', 'atendimento.medico')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->whereBetween('agendas.data', [$data['start'], $data['end']])
            ->whereIn('agendas.status', $status)
            ->where('agendas.arena', $arena)
            ->orderBy('profissionais.nome', 'ASC')
            ->orderBy('pacientes.sexo', 'ASC')
            ->groupBy('abreviacao', 'profissionais.nome', 'pacientes.sexo', 'data_agendamento')
            //->limit(30)
            ->get();

        return $sql;
    }

    public static function getIdade($ano, $mes, $arena = null, $tipo = null)
    {
        $data = Util::periodoMesPorAnoMes($ano, $mes);

        $status = ($tipo == 1) ? [98, 99] : [2, 6, 10, 98, 99];

        $sql = Atendimentos::select(

            [
                'linha_cuidado.nome AS linha_cuidado',
                'profissionais.nome AS medico',
                DB::raw('DATE_FORMAT(agendas.data,"%d") AS dia'),
                DB::raw('
                            CASE
                                WHEN FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 10 THEN "0 ~ 9"
                                WHEN FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) >= 10 AND FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 20 THEN "10 ~ 19"
                                WHEN FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) >= 20 AND FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 30 THEN "20 ~ 29"
                                WHEN FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) >= 30 AND FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 40 THEN "30 ~ 39"
                                WHEN FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) >= 40 AND FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 50 THEN "40 ~ 49"
                                WHEN FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) >= 50 AND FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 65 THEN "50 ~ 64"
                                ELSE "65 ou +"
                            END AS idade
                '),
                DB::raw('COUNT(1) AS total'),
            ]
        )
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('profissionais', 'profissionais.id', '=', 'atendimento.medico')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->whereBetween('agendas.data', [$data['start'], $data['end']])
            ->whereIn('agendas.status', $status)
            ->where('agendas.arena', $arena)
            ->orderBy('abreviacao', 'ASC')
            ->orderBy('profissionais.nome', 'ASC')
            ->orderBy('dia', 'ASC')
            ->orderBy('idade', 'ASC')
            ->groupBy('linha_cuidado', 'medico', 'dia', 'idade')
            //->limit(30)
            ->get();

        return $sql;
    }

}