<?php

namespace App\Http\Helpers\Relatorios;


use App\Agendas;
use App\AtendimentoProcedimentos;
use App\Http\Helpers\Util;
use App\Procedimentos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GorduraHelpers
{

    public static function getGordura($contrato, $status = [6, 8, 10])
    {
        $date = Carbon::createFromFormat('Y-m', date('Y-m'))->subMonth(1);
        $date = Util::periodoMesPorAnoMes($date->format('Y'), $date->format('m'));
        $date['start'] = "2017-03-01 00:00:00";

        $sql = Agendas::join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->join('linha_cuidado', 'agendas.linha_cuidado', '=', 'linha_cuidado.id')
            ->join('pacientes', 'agendas.paciente', '=', 'pacientes.id')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->where('atendimento_procedimentos.faturado', 0)
            ->whereIn('agendas.arena', function ($query) use ($contrato) {
                $query->distinct()->select('arena')
                    ->from('lotes_arena')
                    ->where('lote', $contrato);
            })
            ->whereBetween('agendas.data', [$date['start'], $date['end']])
            ->whereIn('agendas.status', $status)
            ->orderBy('data', 'desc');

        $sql->select([
            'arenas.nome AS unidade',
            'linha_cuidado.nome AS especialidade',
            'agendas.status',
            DB::raw('DATE_FORMAT(agendas.data, "%Y-%m-%d") as data'),
            'procedimentos.sus as procedimento_sus',
            'procedimentos.nome as procedimento',
            DB::raw('DATE_FORMAT(atendimento_procedimentos.finalizacao, "%Y-%m-%d") as finalizacao'),
            DB::raw('SUM(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) AS total')
        ])
            ->groupBy(
            [
                'arenas.nome',
                'linha_cuidado.nome',
                'agendas.status',
                DB::raw('DATE_FORMAT(agendas.data, "%Y-%m-%d")'),
                'procedimentos.sus',
                'procedimentos.nome',
                DB::raw('DATE_FORMAT(atendimento_procedimentos.finalizacao, "%Y-%m-%d")'),

            ]
        )
            ->orderBy('arenas.nome', 'asc');

        return $sql->get();
    }

    /**
     * Ao passar o mês e ano a consulta vai ignorar datas futuras
     *
     * @param $linha_cuidado
     * @param $mes
     * @param ano $
     */
    public static function getGorduraByLinhaCuidado($linha_cuidado, $ano = null, $mes = null)
    {
        $data = [];

        $sql = AtendimentoProcedimentos::select([
            'procedimentos.id',
            'procedimentos.nome',
            DB::raw('SUM(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) AS total')
        ])
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->join('linha_cuidado_procedimentos', 'procedimentos.id', '=', 'linha_cuidado_procedimentos.procedimento')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'linha_cuidado_procedimentos.linha_cuidado')
            ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->where('atendimento_procedimentos.faturado', 0)
            ->whereIn('agendas.status', [6, 10])
            ->where('procedimentos.contador', 1)
            ->groupBy([
                'procedimentos.id',
                'procedimentos.nome'
            ]);

        if (!is_null($ano) && !is_null($mes)) {
            $periodo = Util::periodoMesPorAnoMes($ano, $mes);
            $sql->where('agendas.data', '<', $periodo['start']);
        }

        $result = $sql->get();
        if (!empty($result[0])) {
            foreach ($result as $row) {
                $data[$row->id] = $row->toArray();
            }
        }

        return $data;
    }
}