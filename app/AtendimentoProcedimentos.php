<?php

namespace App;

use App\Http\Helpers\Util;
use App\Jobs\BI\PBI\ProducaoAtendimentoProcedimento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AtendimentoProcedimentos extends Model
{
    protected $table = 'atendimento_procedimentos';


    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            self::setValorProcedimento($model);
        });
    }

    private static function setValorProcedimento($model)
    {
        $atendimento = Atendimentos::find($model->atendimento);
        if (!empty($atendimento) && !in_array($atendimento->status, [98, 99]) && $model->getOriginal('faturado') == 0) {
            $valor_contrato = Procedimentos::getValorProcedimentoContrato(7, $model->procedimento);

            if (!is_null($valor_contrato)) {
                $model->valor_unitario = $valor_contrato->valor_unitario;
            }

            $valor_medico = Procedimentos::getValorProcedimentoMedico($model->procedimento);
            if (!is_null($valor_medico)) {
                $model->valor_medico = $valor_medico->valor_medico;
            }
        }

    }

    public static function getByAtendimento($atendimento, $contador = null)
    {
        $sql = self::select(
            [
                'procedimentos.id',
                'procedimentos.nome',
                'procedimentos.contador',
                'atendimento_procedimentos.quantidade'

            ]
        )
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->where('atendimento', $atendimento);

        if (!empty($contador)) {
            $sql->where('contador', $contador);
        }

        $procedimentos = $sql->get();

        return count($procedimentos) ? $procedimentos : null;
    }

    public static function getProcedimentosFullByLinhaCuidadoAtendimento($atendimento)
    {

        $linha_cuidado = $atendimento->linha_cuidado;
        $procedimentos = Procedimentos::getProcedimentoPatologiaByLinhaCuidadoGrupo($linha_cuidado);

        $res = Procedimentos::distinct()->select(
            [
                'procedimentos.id',
                'procedimentos.nome',
                'procedimentos.contador',
                'procedimentos.sus',
                'procedimentos.maximo',
                'procedimentos.autorizacao as auth',
                'procedimentos.quantidade as qtd',
                'atendimento_procedimentos.autorizacao',
                'atendimento_procedimentos.id AS atendimento_procedimentos_id',
                'atendimento_procedimentos.quantidade AS atendimento_procedimentos_quantidade'
            ]
        )
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->leftJoin('atendimento_procedimentos', function ($join) use ($atendimento) {
                $join->on('procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
                    ->where('atendimento_procedimentos.atendimento', '=', $atendimento->id);
            })
            ->where('linha_cuidado_procedimentos.linha_cuidado', $atendimento->linha_cuidado)
            ->where('procedimentos.ativo', true)
            ->orderBy('procedimentos.nome', 'asc');

        if ($procedimentos) {
            $res->orWhere(function ($query) use ($linha_cuidado, $procedimentos) {
                $query->where('procedimentos.ativo', true)
                    ->where('procedimentos.operacional', true)
                    ->whereIn('procedimentos.id', $procedimentos);
            });
        }

        return $res->get()->toArray();
    }

    public static function getTotalProcedimentoByLinhaCuidadoArenaData($arena, $linha_cuidado, $ano, $mes)
    {
        $date = Util::periodoMesPorAnoMes($ano, $mes);

        $sql = Agendas::select(
            \DB::raw('sum(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) as total, date_format(agendas.data, "%d") AS data')
        )
            ->where('agendas.arena', $arena)
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('linha_cuidado_procedimentos', function ($join) use ($linha_cuidado) {
                $join->on('atendimento_procedimentos.procedimento', '=', 'linha_cuidado_procedimentos.procedimento')
                    ->where('linha_cuidado_procedimentos.linha_cuidado', '=', $linha_cuidado);
            })
            ->whereBetween('agendas.data', array($date['start'], $date['end']))
            ->groupBy(\DB::raw('date_format(agendas.data, "%d")'))
            ->get();

        $data = [];
        foreach ($sql as $row) {
            $data[$row->data] = $row->total;
        }

        return $data;
    }

    public static function getQuantidadeProduzidaMesByLoteLinhaCuidadoData($lote, $linha_cuidado, $faturamento, $faturado = false)
    {
        $data = [];
        $res = Procedimentos::select(
            [
                'procedimentos.id',
                'procedimentos.nome',
                DB::raw('SUM(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) AS total')
            ]
        )
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('atendimento', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('agendas', 'atendimento.agenda', '=', 'agendas.id')
            ->leftjoin('contrato_procedimentos', 'contrato_procedimentos.procedimento', '=', 'procedimentos.id')
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->where('contrato_procedimentos.lote', $lote)
            ->orderBy('procedimentos.nome', 'ASC')
            ->groupBy(
                [
                    'procedimentos.id',
                    'procedimentos.nome'
                ]
            );

        if ($faturado) {
            $res->join('faturamento_procedimentos', 'faturamento_procedimentos.atendimento_procedimento', '=', 'atendimento_procedimentos.id');
            $res->where('faturamento_procedimentos.faturamento', $faturamento);
            $res->where('faturamento_procedimentos.status', '=', 1);
        } else {
            $_faturamento = Faturamento::find($faturamento);
            $date = Util::periodoMesPorAnoMes($_faturamento->ano, $_faturamento->mes);

            $res->whereBetween('agendas.data', [$date['start'], $date['end']]);
            $res->whereIn('agendas.status', [6, 8, 10, 98, 99]);
        }


        foreach ($res->get() as $row) {
            $data[$row->id] = $row->toArray();
        }

        return $data;
    }

    public static function getQuantidadeProduzidaMesByLoteProcedimento($lote, $faturamento, $procedimento, $faturado = false, $status = [6, 8, 10, 98, 99], $profissional = null)
    {
        $procedimentos = is_array($procedimento) ? $procedimento : array($procedimento);

        $data = [];
        $res = Procedimentos::select(
            [
                'procedimentos.id',
                'procedimentos.nome',
                DB::raw('SUM(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) AS total')
            ]
        )
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('atendimento', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('agendas', 'atendimento.agenda', '=', 'agendas.id')
            ->join('lotes_arena', 'lotes_arena.arena', '=', 'agendas.arena')
            ->whereIn('atendimento_procedimentos.procedimento', $procedimentos)
            ->where('lotes_arena.lote', $lote)
            ->groupBy(
                [
                    'procedimentos.id',
                    'procedimentos.nome'
                ]
            );

        if ($faturado) {
            $res->join('faturamento_procedimentos', 'faturamento_procedimentos.atendimento_procedimento', '=', 'atendimento_procedimentos.id');
            $res->where('faturamento_procedimentos.faturamento', $faturamento);
            $res->where('faturamento_procedimentos.status', 1);
        } else {
            $_faturamento = Faturamento::find($faturamento);
            $date = Util::periodoMesPorAnoMes($_faturamento->ano, $_faturamento->mes);

            $res->whereBetween('agendas.data', [$date['start'], $date['end']]);
            $res->whereIn('agendas.status', $status);
        }

        if (!is_null($profissional)) {
            $res->where('atendimento.medico', $profissional);
        }

        $data = $res->get();
        $_data = null;
        if (!empty($data[0])) {
            $_data = !is_array($procedimento) ? $data[0] : $data;
        }

        return $_data;
    }

    public static function getQuantidadeAbsenteismoByLoteProcediemnto($lote, $faturamento, $procedimentos, $status = [0, 1, 5, 7])
    {
        $_faturamento = Faturamento::find($faturamento);
        $date = Util::periodoMesPorAnoMes($_faturamento->ano, $_faturamento->mes);

        $data = [];
        $res = Procedimentos::select(
            [
                'procedimentos.id',
                'procedimentos.nome',
                DB::raw('COUNT(procedimentos.quantidade * procedimentos.multiplicador) AS total')
            ]
        )
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('agendas', 'procedimentos.id', '=', 'agendas.procedimento')
            ->join('lotes_arena', 'lotes_arena.arena', '=', 'agendas.arena')
            ->whereIn('agendas.procedimento', $procedimentos)
            ->where('lotes_arena.lote', $lote)
            ->groupBy(
                [
                    'procedimentos.id',
                    'procedimentos.nome'
                ]
            )->whereBetween('agendas.data', [$date['start'], $date['end']])
            ->whereIn('agendas.status', $status);

        return $res->get();
    }

    public static function getProcedimentoPrincipalByAtendimento($atendimento)
    {
        $data = AtendimentoProcedimentos::select(
            [
                'procedimentos.*'
            ]
        )
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->where('atendimento_procedimentos.atendimento', $atendimento)
            ->where('procedimentos.principal', 1)->get();

        return !empty($data[0]) ? $data[0] : null;

    }

}
