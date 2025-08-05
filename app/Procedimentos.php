<?php

namespace App;

use App\Http\Helpers\Mask;
use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDOException;

class Procedimentos extends Model
{
    protected $table = 'procedimentos';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->getAttributes() as $key => $value) {
                $model->$key = Util::String2DB($value);
            }

            $model->cid_primario = empty($model->cid_primario) ? null : $model->cid_primario;
            $model->cid_secundario = empty($model->cid_secundario) ? null : $model->cid_secundario;

            Cache::flush();
        });
    }

    public static function ComboByLinhaCuidado($linha_cuidado)
    {
        $key = 'get-procedimentos-por-linha-cuidado-' . $linha_cuidado;
        $data = [];

        $_data = Procedimentos::select(
            [
                'procedimentos.nome',
                'procedimentos.sus',
                'procedimentos.id'
            ]
        )
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->where('procedimentos.ativo', 1)
            ->orderBy('procedimentos.nome', 'asc')->get();

        if (!Cache::has($key)) {

            if (count($_data)) {
                foreach ($_data as $row) {
                    $data[$row->id] = Mask::ProcedimentoSUS($row->sus) . " - {$row->nome}";
                }
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function ComboPrincipaisByLinhaCuidado($linha_cuidado)
    {
        $key = 'get-ComboPrincipaisByLinhaCuidado-' . $linha_cuidado;
        $data = [];

        $_data = Procedimentos::select(
            [
                'procedimentos.nome',
                'procedimentos.sus',
                'procedimentos.id'
            ]
        )
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->where('procedimentos.ativo', 1)
            ->orderBy('procedimentos.nome', 'asc')->get();

        if (!Cache::has($key)) {

            if (count($_data)) {
                foreach ($_data as $row) {
                    $data[$row->id] = $row->nome;
                }
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getProcedimentosByGrupo($params = array())
    {
        $sql = Procedimentos::select(
            [
                'procedimentos.*',
            ]
        )
            ->where('procedimentos.ativo', true)
            ->orderBy('procedimentos.sus', 'asc')
            ->orderBy('procedimentos.nome', 'asc');

        if (!empty($params['grupo'])) {
            $sql->where(\DB::raw('SUBSTRING(procedimentos.sus, 1,4)'), '=', $params['grupo']);
        }

        if (!empty($params['especialidade'])) {
            $procedimentos = self::getProcedimentosIDByLinhaCuidado($params['especialidade']);

            $sql->whereIn('procedimentos.id', $procedimentos);
        }

        if (!empty($params['procedimento'])) {
            $sql->where('procedimentos.id', $params['procedimento']);
        } else {

        }

        return $sql->get();
    }

    public static function Combo()
    {
        return self::lists('nome', 'id')->toArray();
    }

    public static function getConsolidadosByAtendimento($atendimento)
    {
        $res = AtendimentoProcedimentos::select(
            [
                'procedimentos.id',
                'procedimentos.nome'
            ]
        )
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->where('atendimento_procedimentos.atendimento', $atendimento)
            ->where('procedimentos.contador', 1)
            ->orderBy('procedimentos.nome', 'asc')
            ->get();

        return $res;
    }

    /**
     * @param $linha_cuidado
     * @return array
     */
    public static function getProcedimentoPatologiaByLinhaCuidadoGrupo($linha_cuidado)
    {
        $procedimentos = [];

        switch ($linha_cuidado) {
            case 4 :
            case 5 :
            case 6 :
            case 49 :
            case 19 :
                $procedimentos[] = 11;
                $procedimentos[] = 12;
                break;
            case 1 :
                $procedimentos[] = 2;
                $procedimentos[] = 3;
                $procedimentos[] = 4;
                $procedimentos[] = 5;
                $procedimentos[] = 11;
                $procedimentos[] = 12;
                $procedimentos[] = 13;
                $procedimentos[] = 6;
                break;
            case 2 :
                $procedimentos[] = 13;
                $procedimentos[] = 17;
                $procedimentos[] = 11;
                $procedimentos[] = 12;
                $procedimentos[] = 4;
                $procedimentos[] = 3;
                $procedimentos[] = 6;
                break;
            case 3 :
                $procedimentos[] = 12;
                $procedimentos[] = 11;
                break;
            case 21 :
                $procedimentos[] = 11;
                $procedimentos[] = 12;
                break;
            case 7 :
            case 8 :
            case 9 :
            case 26 :
            case 28 :
            case 32 :
            case 35 :
            case 37 :
            case 40 :
            case 46 :
                $procedimentos[] = 12;
                break;
        }

        return $procedimentos;
    }


    public static function getProcedimentosIDByLinhaCuidado($linha_cuidado)
    {
        $_res = Procedimentos::select(
            [
                'procedimentos.id',
                'procedimentos.nome',
            ]
        )
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->orderBy('procedimentos.nome', 'ASC')
            ->lists('procedimentos.id')
            ->toArray();

        $patologia = self::getProcedimentoPatologiaByLinhaCuidadoGrupo($linha_cuidado);

        return array_merge($_res, $patologia);
    }

    public static function getProcedimentosFaturadosPorLinhaCuidadoAgenda($faturamento, $linha_cuidado, $lote)
    {
        $data = Procedimentos::select(
            [
                'procedimentos.id AS cod_procedimento',
                'procedimentos.sus AS codigo',
                'procedimentos.nome AS procedimento',
                \DB::raw('SUM(faturamento_procedimentos.quantidade) AS faturado'),
            ]
        )
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('faturamento_procedimentos', 'faturamento_procedimentos.atendimento_procedimento', '=',
                'atendimento_procedimentos.id')
            ->where('agendas.linha_cuidado', $linha_cuidado)
            ->where('faturamento_procedimentos.faturamento', $faturamento)
            ->where('faturamento_procedimentos.status', '=', 1)
            ->orderBy('procedimentos.nome', 'ASC')
            ->groupBy(
                'procedimentos.id',
                'procedimentos.sus',
                'procedimentos.nome'
            )
            ->get();

        return $data;
    }


    public static function getProducaoMesLinhaCuidadoAgenda(
        $linha_cuidado,
        $status = [6, 8, 10],
        $date = null,
        $direcao = null,
        $periodo = null
    )
    {
        $data = [];
        $sql = Procedimentos::select(
            [
                'procedimentos.id',
                \DB::raw('SUM(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) AS produzido'),
            ]
        )
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->where('agendas.linha_cuidado', $linha_cuidado)
            ->where('agendas.data', '>=', '2017-03-01 00:00:00')
            ->whereIn('atendimento.status', $status)
            ->groupBy(
                'procedimentos.id'
            );

        if (!is_null($date)) {
            $sql->where('agendas.data', $direcao, $date);
        }

        if (!is_null($periodo)) {
            $sql->whereBetween('agendas.data', [$periodo['start'], $periodo['end']]);
        }

        foreach ($sql->get() as $procedimento) {
            $data[$procedimento->id] = $procedimento->produzido;
        }

        return $data;
    }

    public static function getContratoProcedimentoListByLote($lote)
    {
        $sql = ContratoProcedimentos::select(
            [
                'contrato_procedimentos.procedimento',
                'contrato_procedimentos.valor_unitario',
                'contrato_procedimentos.quantidade',
            ]
        )
//            ->whereNotIn('procedimento', [11, 12])
            ->where('lote', $lote)
            ->get();

        $data = array();
        foreach ($sql as $contrato) {
            $data[$contrato->procedimento] = $contrato->toArray();
        }

        return $data;
    }

    public static function getProcedimentoContratoByLoteLinhaCuidado($lote, $linha_cuidado)
    {
        $data = Procedimentos::select(
            [
                'procedimentos.id AS cod_procedimento',
                'procedimentos.sus AS codigo',
                'procedimentos.nome AS procedimento',
                'contrato_procedimentos.valor_unitario AS valor_contrato',
                'contrato_procedimentos.quantidade AS quantidade_contrato'
            ]
        )
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->leftjoin('contrato_procedimentos', 'contrato_procedimentos.procedimento', '=', 'procedimentos.id')
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->where('contrato_procedimentos.lote', $lote)
            ->orderBy('procedimentos.nome', 'ASC')
            ->get();

        return $data;
    }

    public static function getValorProcedimentoContrato($lote = 7, $procedimento)
    {
        $key = 'getValorProcedimentoContratoo-' . $lote . "--" . $procedimento;
        $data = null;

//        if (!Cache::has($key)) {

            $sql = ContratoProcedimentos::select(['contrato_procedimentos.*'])
                ->where('contrato_procedimentos.procedimento', $procedimento)
                ->where('contrato_procedimentos.lote', $lote)
                ->get();

            if (count($sql)) {
                $data = !empty($sql[0]) ? $sql[0] : null;
//                Cache::put($key, $data, CACHE_DAY);
            }

//        } else {
//            $data = Cache::get($key);
//        }

        return $data;
    }

    public static function getValorProcedimentoMedico($procedimento)
    {
        $key = 'getValorProcedimentoMedico-' . $procedimento;
        $data = null;

//        if (!Cache::has($key)) {

            $sql = Procedimentos::select(['procedimentos.valor_medico'])
                ->where('procedimentos.id', $procedimento)
                ->get();

            if (count($sql)) {
                $data = !empty($sql[0]) ? $sql[0] : null;
//                Cache::put($key, $data, CACHE_DAY);
            }

//        } else {
//            $data = Cache::get($key);
//        }

        return $data;
    }

    public static function getByLinhaCuidado($linha_cuidado)
    {
        $procedimentos = Procedimentos::getProcedimentoPatologiaByLinhaCuidadoGrupo($linha_cuidado);

        $sql = Procedimentos::distinct()->select('procedimentos.*')
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->where('procedimentos.ativo', true)
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->orderBy('procedimentos.obrigatorio', 'desc')
            ->orderBy('procedimentos.nome', 'asc');

        if (count($procedimentos) > 0) {
            $sql->whereIn('procedimentos.id', $procedimentos, 'or');
        }

        $data = $sql->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function AbsenteismoCancelamento($periodo, $especialidade)
    {
        $data = [];
        //$status = [2];
        $status = [7];
        //$status = [2,7];

        $total = Agendas::select(
            [
                'agendas.id'
            ]
        )
            ->whereBetween('agendas.data', $periodo)
            ->whereIn('agendas.status', $status)
            ->where('agendas.linha_cuidado', $especialidade)
            ->count();

        if ($total > 0) {
            $_procedimentos = self::getProcedimentoObrigatoriosByLinhaCuidado($especialidade);

            $procedimentos = Procedimentos::select(
                [
                    'procedimentos.id',
                    'procedimentos.nome',
                    'procedimentos.sus',
                    DB::raw("{$total} * procedimentos.multiplicador AS quantidade")
                ]
            )
                ->whereIn('id', $_procedimentos)
                ->groupBy([
                        'procedimentos.id',
                        'procedimentos.nome',
                        'procedimentos.sus'
                    ]
                )
                ->orderBy('procedimentos.nome', 'asc')
                ->get();

            foreach ($procedimentos as $procedimento) {
                $data[$procedimento->id] = $procedimento->toArray();
            }
        }

        return $data;
    }

    public static function getProcedimentoObrigatoriosByLinhaCuidado($linha_cuidado)
    {
        $data = Procedimentos::select(
            [
                'procedimentos.*'
            ]
        )
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->where('procedimentos.obrigatorio', true)
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->get()
            ->toArray();

        $patologia = self::getProcedimentoPatologiaByLinhaCuidadoGrupo($linha_cuidado);
        return array_merge(array_column($data, 'id'), $patologia);
    }

    public static function ComboProcedimentoAgenda()
    {
        $ids = Agendas::distinct()->whereNotNull('procedimento')->lists('procedimento');

        return Procedimentos::where('ativo', true)->whereIn('id', $ids)->orderBy('nome', 'asc')->lists('nome', 'id')->toArray();
    }

    public static function getAgendadoPeriodo($linha_cuidado, $start, $end)
    {
        $data = [];

        $sql = Agendas::select(
            [
                'agendas.procedimento',
                DB::raw("COUNT(agendas.id) AS total")
            ]
        )
            ->whereBetween('agendas.data', [$start, $end])
            ->where('agendas.linha_cuidado', $linha_cuidado)
            ->whereNotNull('agendas.procedimento')
            ->groupBy('agendas.procedimento')
            ->get();

        if ($sql) {
            foreach ($sql as $row) {
                $data[$row->procedimento] = $row->total;
            }
        }

        return $data;
    }

    public static function getProducaoPeriodo($linha_cuidado, $start, $end)
    {
        $data = [];

        $sql = Agendas::select(
            [
                'atendimento_procedimentos.procedimento',
                DB::raw("SUM(atendimento_procedimentos.multiplicador * atendimento_procedimentos.quantidade) AS total")
            ]
        )
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->where('agendas.linha_cuidado', $linha_cuidado)
            ->whereBetween('agendas.data', [$start, $end])
            ->groupBy('atendimento_procedimentos.procedimento')
            ->get();

        if ($sql) {
            foreach ($sql as $row) {
                $data[$row->procedimento] = $row->total;
            }
        }

        return $data;
    }

    public static function getFaturamento($linha_cuidado, $faturamento)
    {
        $data = [];
        $sql = Agendas::select(
            [
                'atendimento_procedimentos.procedimento',
                DB::raw("SUM(faturamento_procedimentos.quantidade) AS total")
            ]
        )
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('faturamento_procedimentos', 'faturamento_procedimentos.atendimento_procedimento', '=', 'atendimento_procedimentos.id')
            ->where('faturamento_procedimentos.faturamento', $faturamento)
            ->where('agendas.linha_cuidado', $linha_cuidado)
            ->where('faturamento_procedimentos.status', 1)
            ->groupBy('atendimento_procedimentos.procedimento')
            ->get();

        if ($sql) {
            foreach ($sql as $row) {
                $data[$row->procedimento] = $row->total;
            }
        }

        return $data;
    }


    public function saveData($data)
    {
        try {
            if (isset($data['_token'])) {
                unset($data['_token']);
            }

            $linha_cuidado = $data['linha_cuidado'];
            unset($data['linha_cuidado']);

            $model = empty($data['id']) ? new Procedimentos() : $this->find($data['id']);
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $model->$key = $value;
                }
            }
            $model->save();

            if (!empty($model->id)) {
                LinhaCuidadoProcedimentos::where('procedimento', '=', $model->id)->delete();
                foreach ($linha_cuidado as $row) {
                    $_model = new LinhaCuidadoProcedimentos();
                    $_model->procedimento = $model->id;
                    $_model->linha_cuidado = $row;
                    $_model->save();
                }
            }

            return true;
        } catch (PDOException $e) {

            return false;
        }
    }

    public static function ByLinhaCuidado($linha_cuidado)
    {
        $data = [];
        $_res = Procedimentos::select(
            [
                'procedimentos.id',
                'procedimentos.nome',
            ]
        )
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->orderBy('procedimentos.nome', 'ASC')
            ->get()
            ->toArray();

        if ($_res) {
            foreach ($_res as $row) {
                $data[$row['id']] = $row['nome'];
            }
        }

        return $data;
    }

    public static function getByArenas($arenas)
    {

        $procedimentos = LotesArena::distinct()->select([
            'procedimentos.nome',
            'procedimentos.id',
            'procedimentos.sus',
            'procedimentos.complexidade',
            'procedimentos.modalidade'
        ])
            ->join('arenas_linha_cuidado', 'arenas_linha_cuidado.arena', '=', 'lotes_arena.arena')
            ->join('linha_cuidado', 'arenas_linha_cuidado.linha_cuidado', '=', 'linha_cuidado.id')
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.linha_cuidado', '=', 'linha_cuidado.id')
            ->join('procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'procedimentos.id')
            ->whereIn('arenas_linha_cuidado.arena', $arenas)
            ->where('procedimentos.ativo', 1)
            ->where('linha_cuidado.ativo', 1)
            ->orderBy('procedimentos.nome', 'asc')
            ->get();

        return count($procedimentos) ? $procedimentos : null;
    }

    public static function getProcedimentos()
    {
        $sql = self::select([
            'procedimentos.id',
            'procedimento_complexidade.nome AS complexidade',
            'procedimento_modalidades.nome AS modalidade',
            \DB::raw('CONCAT(cid_primario.codigo, " - ", cid_primario.descricao) AS cid_primario'),
            \DB::raw('CONCAT(cid_secundario.codigo, " - ", cid_secundario.descricao) AS cid_secundario'),
            'procedimentos.nome',
            'procedimentos.quantidade',
            'procedimentos.obrigatorio',
            'procedimentos.sus',
            'procedimentos.forma_faturamento',
            'procedimentos.maximo',
            'procedimentos.cbo',
            'procedimentos.contador',
            'procedimentos.ativo',
            'procedimentos.ordem',
            'procedimentos.servico_bpa',
            'procedimentos.class_bpa',
            'procedimentos.multiplicador',
            'procedimentos.multiplicador_medico',
            'procedimentos.sexo',
            'procedimentos.autorizacao',
            'procedimentos.principal',
            'procedimentos.created_at',
            'procedimentos.updated_at',

        ])
            ->leftJoin('procedimento_complexidade', 'procedimentos.complexidade', '=', 'procedimento_complexidade.id')
            ->leftJoin('procedimento_modalidades', 'procedimentos.modalidade', '=', 'procedimento_modalidades.id')
            ->leftJoin('cid AS cid_primario', 'procedimentos.cid_primario', '=', 'cid_primario.id')
            ->leftJoin('cid AS cid_secundario', 'procedimentos.cid_secundario', '=', 'cid_secundario.id')
            ->orderBy('procedimentos.nome', 'asc');

        $data = $sql->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function getConsolidados($arr = [])
    {
        $res = Procedimentos::select("*")
            ->where('ativo', 1)
            ->where('forma_faturamento', 1)
            ->orderBy('nome', 'asc');

        if (count($arr)) {
            $res->whereIn('procedimentos.id', $arr);
        }

        return $res->get();
    }

    public static function ComboConsolidados($arr = [])
    {
        $procedimentos = self::getConsolidados($arr);

        $data = [];

        if ($procedimentos) {
            foreach ($procedimentos as $procedimento) {
                $data[$procedimento->id] = $procedimento->nome;
            }
        }

        return $data;
    }

}
