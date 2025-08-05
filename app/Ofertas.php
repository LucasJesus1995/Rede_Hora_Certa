<?php

namespace App;

use App\Http\Helpers\DataHelpers;
use App\Http\Helpers\SQLHelpers;
use App\Http\Helpers\Util;
use App\Traits\ModelTraits;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ofertas extends Model
{
    use ModelTraits, SoftDeletes;

    protected $table = 'ofertas';


    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

            if (empty($model->id)) {
                $model->user = Auth::user()->id;
            }

            if (empty($model->equipamento)) {
                unset($model->equipamento);
            }

            if (!empty($model->data) && strstr($model->data, '/')) {
                $model->data = Util::Date2DB($model->data);
            }

            if (!empty($model->data_aprovacao) && strstr($model->data_aprovacao, '/')) {
                $model->data_aprovacao = Util::Date2DB($model->data_aprovacao);
            }

        });
    }

    public static function saveOfertas(array $data)
    {
        $_repetir_semana = null;

        DB::transaction(function () use ($data, &$_repetir_semana) {
            try {
                $isInsert = empty($data['id']);

                $oferta = (new Ofertas())->_save($data);
                DB::commit();

                return $oferta;
            } catch (\Exception $e) {
                DB::rollback();

                throw new \Exception($e->getMessage());
            }
        });
    }

    private static function duplicarOfertaMes(Ofertas $oferta)
    {
        $data = Carbon::createFromFormat("Y-m-d", $oferta->data);
        $mes = $data->format("m");

        for ($i = 1; $i <= 6; $i++) {
            $_data = $data->addDay(7);
            $_mes = $_data->format("m");

            if ($mes != $_mes) {
                break;
            }

            $_oferta = $oferta->replicate();
            $_oferta->data = $_data->toDateString();
            $_oferta->save();

            $oferta_statuss = self::getStatus($oferta->id);

            if (!empty($oferta_statuss)) {
                foreach ($oferta_statuss AS $oferta_status) {
                    $_oferta_status = $oferta_status->replicate();
                    $_oferta_status->oferta = $_oferta->id;
                    $_oferta_status->save();
                }
            }

            $oferta_procedimentos = self::getProcedimentos($oferta->id);
            if (!empty($oferta_procedimentos)) {
                foreach ($oferta_procedimentos AS $oferta_procedimento) {
                    $_oferta_procedimento = $oferta_procedimento->replicate();
                    $_oferta_procedimento->oferta = $_oferta->id;
                    $_oferta_procedimento->save();
                }
            }
        }

    }

    public static function getStatus($oferta)
    {
        $data = OfertaStatus::where('oferta', $oferta)->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function getProcedimentos($oferta)
    {
        $data = OfertaProcedimentos::where('oferta', $oferta)->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function getOcorrencias($oferta)
    {
        $data = OfertaOcorrencias::where('oferta', $oferta)->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function setOfertaStatus($oferta, $status)
    {
        $oferta = self::find($oferta);
        $oferta->status = $status;
        $oferta->save();

        (new OfertaStatus())->_save(['oferta' => $oferta->id, 'status' => $status]);
    }

    public static function setOfertaProcedimentos($oferta, $procedimento)
    {
        (new OfertaProcedimentos())->_save(['oferta' => $oferta, 'procedimento' => $procedimento]);
    }

    public static function getOfertasImportacao()
    {
        $data = [];
        $sql = Ofertas::select(
            [
                '*'
            ]
        )
            ->where('aberta', true)
            ->limit(10)
            ->get();

        if (!empty($sql[0])) {
            foreach ($sql AS $row) {
                $data[$row->id] = "{$row->arena} ({$row->linha_cuidado}) \n\n" .

                    $row->id;
            }
        }

        return $data;
    }

    public static function getEscala($mes, $unidade = null)
    {
        $periodo = Util::periodoMesPorAnoMes(date('Y'), $mes);
        $sql = Ofertas::select(
            [
                DB::raw("DATE_FORMAT(ofertas.data, '%d') AS dia"),
                'linha_cuidado.nome AS linha_cuidado',
                'arenas.nome AS arena',
                DB::raw(SQLHelpers::getOfertasMes()),
                DB::raw(SQLHelpers::getOfertasSemana()),
                'profissionais.nome AS profissional',
                DB::raw(SQLHelpers::getOfertasStatus()),
                'arena_equipamentos.nome AS equipamento',
                DB::raw(SQLHelpers::getOfertasClassificacao()),
                DB::raw(SQLHelpers::getOfertasPeriodo()),
                DB::raw("MIN(ofertas.hora_inicial) AS HorarioInicial"),
                DB::raw("MAX(ofertas.hora_final) AS HorarioFinal"),
                DB::raw("SUM(ofertas.quantidade) AS quantidade"),
                DB::raw(SQLHelpers::getOfertasNatureza()),
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'ofertas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'ofertas.linha_cuidado')
            ->join('profissionais', 'profissionais.id', '=', 'ofertas.profissional')
            ->leftjoin('arena_equipamentos', 'arena_equipamentos.id', '=', 'ofertas.equipamento')
            ->whereBetween('ofertas.data', $periodo)
            ->whereNotIn('ofertas.status', [15, 16, 17, 18, 19])
            ->groupBy(
                [
                    DB::raw("DATE_FORMAT(ofertas.data, '%d')"),
                    'ofertas.linha_cuidado',
                    'ofertas.arena',
                    DB::raw("DATE_FORMAT(ofertas.data, '%m')"),
                    DB::raw("WEEKDAY(ofertas.data)"),
                    'ofertas.profissional',
                    'ofertas.status',
                    'ofertas.equipamento',
                    'ofertas.periodo',
                    'ofertas.classificacao',
                    'ofertas.natureza',
                ]
            );


        if (!empty($unidade)) {
            $sql->where('ofertas.arena', $unidade);
        }

        return $sql->get();
    }

    public static function getRelatorioCompleto(array $params)
    {
        $sql_procedimentos = "SELECT GROUP_CONCAT(procedimentos.nome SEPARATOR ';') FROM procedimentos JOIN oferta_procedimentos op on procedimentos.id = op.procedimento WHERE op.oferta = ofertas.id";
        $sql_ocorrencias = "SELECT GROUP_CONCAT(oferta_ocorrencias.descricao SEPARATOR ';') FROM oferta_ocorrencias WHERE oferta_ocorrencias.oferta = ofertas.id";

        $sql = Ofertas::select(
            [
                'ofertas.id',
                'arena_equipamentos.nome AS equipamento',
                'ofertas.quantidade',
                DB::raw("DATE_FORMAT(ofertas.data_aprovacao, '%d/%m/%Y') as data_aprovacao"),
                DB::raw("DATE_FORMAT(ofertas.data, '%d/%m/%Y') AS data"),
                DB::raw("($sql_procedimentos) AS procedimentos"),
                DB::raw("($sql_ocorrencias) AS ocorrencias"),
                'ofertas.hora_inicial',
                'ofertas.hora_final',
                DB::raw(SQLHelpers::getOfertasPeriodo()),
                DB::raw(SQLHelpers::getOfertasNatureza()),
                DB::raw(SQLHelpers::getOfertasSemana()),
                DB::raw("DATE_FORMAT(ofertas.data, '%d') AS dia"),
                'ofertas.observacao',
                DB::raw(SQLHelpers::getOfertasClassificacao()),
                DB::raw("IF(ofertas.aberta>0,'Sim','Não') AS aberta"),
                DB::raw(SQLHelpers::getOfertasStatus()),
                'arenas.nome AS arena',
                'linha_cuidado.nome AS especialidade',
                'profissionais.nome AS medico',
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'ofertas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'ofertas.linha_cuidado')
            ->join('profissionais', 'profissionais.id', '=', 'ofertas.profissional')
            ->leftjoin('arena_equipamentos', 'arena_equipamentos.id', '=', 'ofertas.equipamento')
        ;

        if (!empty($params['mes'])) {
            $date = Util::periodoMesPorAnoMes(date('Y'), $params['mes']);

            $sql->whereBetween('ofertas.data', [$date['start'], $date['end']]);
        }

        if (!empty($params['linha_cuidado'])) {
            $sql->where('ofertas.linha_cuidado', $params['linha_cuidado']);
        }

        if (!empty($params['profissional'])) {
            $sql->where('ofertas.profissional', $params['profissional']);
        }

        if (!empty($params['unidade'])) {
            $sql->where('ofertas.arena', $params['unidade']);
        }

        $sql->orderBy('ofertas.data', 'asc');
        $sql->orderBy('ofertas.hora_inicial', 'asc');

        $data = $sql->get();

        return !empty($data[0]) ? $data : null;
    }

    public function _save(array $data)
    {
        $_data = null;
        if (!empty($data['codigo'])) {
            $__data = self::where('codigo', $data['codigo'])->get();

            if (!empty($__data[0])) {
                $_data = $__data[0];
                $data['id'] = $_data->id;
            }
        }

        $_data = !empty($_data->id) ? $_data : $this;

        $columns = \DB::connection()->getSchemaBuilder()->getColumnListing($this->getTable());

        if (count($columns)) {
            try {
                foreach ($columns AS $column) {
                    if (array_key_exists($column, $data)) {
                        $_data->$column = $data[$column];
                    }
                }

                $_data->save();
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }

        return $_data;
    }

}
