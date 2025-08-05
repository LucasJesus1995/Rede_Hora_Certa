<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Faturamento extends Model
{
    protected $table = 'faturamento';

    public static function Combo()
    {
        $key = 'faturamento-combo';
        $_data = array();

        if (!Cache::has($key)) {
            $data = self::select('ano', 'mes', 'id')->orderBy('ano', 'desc')->orderBy('mes', 'desc')->get()->toArray();

            if (count($data)) {
                foreach ($data AS $row) {
                    $mes = Util::getMesNome($row['mes']);
                    $_data[$row['ano']][$row['id']] = "{$mes}";
                }

                Cache::put($key, $_data, CACHE_DAY);
            }

        } else {
            $_data = Cache::get($key);
        }

        return $_data;
    }

    public static function getFaturamentoDetalhes($lote, $faturamento)
    {
        $data = FaturamentoProcedimento::select(
            [
                DB::raw("DATE_FORMAT(agendas.data,'%d/%m/%Y') as data"),
                'arenas.nome AS arena',
                'linha_cuidado.nome AS linha_cuidado',
                'profissionais.nome AS medico',
                'procedimento_complexidade.sigla AS complexidade',
                'procedimentos.sus',
                'procedimentos.nome AS procedimento',
                DB::raw("SUM(faturamento_procedimentos.quantidade) as quantidade"),
                DB::raw("(SELECT FORMAT(valor_unitario,2, 'de_DE') FROM sistema_ciesglobal_org.contrato_procedimentos  WHERE lote  = {$lote} AND procedimento = procedimentos.id LIMIT 1) AS valor_unitario"),
            ]
        )
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
            ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('profissionais', 'profissionais.id', '=', 'atendimento.medico')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->join('procedimento_complexidade', 'procedimentos.complexidade', '=', 'procedimento_complexidade.id')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->where('faturamento_procedimentos.faturamento', $faturamento)
            ->where('faturamento_procedimentos.lote', $lote)
//            ->where('procedimentos.id', 227)
            ->orderBy('agendas.data', 'asc')
            ->orderBy('arenas.nome', 'asc')
            ->orderBy('profissionais.nome', 'asc')
            ->groupBy(
                [
                    DB::raw("DATE_FORMAT(agendas.data,'%Y-%m-%d')"),
                    'arenas.id',
                    'linha_cuidado.id',
                    'profissionais.id',
                    'procedimentos.id',
                ]
            )
            ->get();

        return $data;
    }

    public static function ComboFaturamentoFinalizado()
    {
        $key = 'faturamento-combo-faturamento-finalizado';
        $_data = array();

        if (!Cache::has($key)) {
            $data = self::select('ano', 'mes', 'id')->where('status', 3)->orderBy('ano', 'desc')->orderBy('mes', 'desc')->get()->toArray();

            if (count($data)) {
                foreach ($data AS $row) {
                    $mes = Util::getMesNome($row['mes']);
                    $_data[$row['ano']][$row['id']] = "{$mes}";
                }

                Cache::put($key, $_data, CACHE_DAY);
            }

        } else {
            $_data = Cache::get($key);
        }

        return $_data;
    }

    public static function Relatorio($params)
    {

        $sql = FaturamentoProcedimento::select(
            [
                'lotes.nome AS lote_nome',
                'linha_cuidado.nome AS linha_cuidado_nome',
                'linha_cuidado.id AS linha_cuidado_id',
                'lotes.id AS lote_id',
                'faturamento.id AS faturamento_id',
                'procedimentos.id AS procedimentos_id',
                'procedimentos.nome AS procedimentos_nome',
                DB::raw('SUM(faturamento_procedimentos.quantidade) AS quantidade')
            ]
        )
            ->join('faturamento', 'faturamento_procedimentos.faturamento', '=', 'faturamento.id')
            ->join('atendimento_procedimentos', 'faturamento_procedimentos.atendimento_procedimento', '=', 'atendimento_procedimentos.id')
            ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('lotes', 'faturamento_procedimentos.lote', '=', 'lotes.id')
            ->join('linha_cuidado', 'agendas.linha_cuidado', '=', 'linha_cuidado.id')
            ->join('arenas', 'agendas.arena', '=', 'arenas.id')
            ->join('procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->where("faturamento_procedimentos.status", 1)
            ->orderBy('lote_nome', 'asc')
            ->orderBy('linha_cuidado_nome', 'asc')
            ->orderBy('procedimentos_nome', 'asc')
            ->groupBy([
                'lotes.nome',
                'linha_cuidado.id',
                'lotes.id',
                'faturamento.id',
                'procedimentos.id',
                'procedimentos.nome'
            ]);

        if (!empty($params['faturamento'])) {
            $sql->where('faturamento.id', $params['faturamento']);
        }

        if (!empty($params['lote'])) {
            $sql->where('lotes.id', $params['lote']);
        }

        if (!empty($params['arena'])) {
            $sql->where('arenas.id', $params['arena']);
        }

        if (!empty($params['linha_cuidado'])) {
            $sql->where('linha_cuidado.id', $params['linha_cuidado']);
        }

        if (!empty($params['medico'])) {
            $sql->where('atendimento_procedimentos.profissional', $params['medico']);
        }

        $data = [];
        $res = $sql->get()->toArray();

        if (!empty($res)) {
            foreach ($res AS $row) {
                $data[$row['lote_nome']][$row['linha_cuidado_nome']][] = $row;
            }
        }

        return $data;
    }

    public static function getFaturamentoAnoMes($ano, $mes)
    {
        $data = self::where('ano', $ano)->where('mes', $mes)->get();

        return count($data) ? $data[0] : null;
    }

    public static function getFaturamentoAberto()
    {
        $data = self::where('status', 2)->get();

        return count($data) ? $data[0] : null;
    }


}
