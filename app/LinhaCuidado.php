<?php

namespace App;

use App\Http\Helpers\UsuarioHelpers;
use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LinhaCuidado extends Model
{
    protected $table = 'linha_cuidado';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

            foreach ($model->getAttributes() AS $key => $value) {
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static function Combo()
    {
        $data = self::where('ativo', true)->orderBy('nome', 'asc')->lists('nome', 'id')->toArray();

        return $data;
    }

    public static function get($id)
    {
        $key = 'get-linha_cuidado-' . $id;

        if (!Cache::has($key)) {
            $data = LinhaCuidado::find($id)->toArray();

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function ByArena($id, $full = false)
    {
        $sql = ArenasLinhaCuidado::where('arena', $id)
            ->select(['linha_cuidado.id', 'linha_cuidado.nome', 'linha_cuidado.abreviacao'])
            ->join('arenas', 'arenas.id', '=', 'arenas_linha_cuidado.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'arenas_linha_cuidado.linha_cuidado')
            ->orderBy('linha_cuidado.nome', 'asc');

        $res = $sql->get();

        $data = [];
        if ($res && !$full) {
            foreach ($res AS $row) {
                $data[$row->id] = $row->nome;
            }

            asort($data);
        }

        if ($full) {
            $data = $res;
        }

        return $data;
    }

    public static function getMedicamentosByLinhaCuidado($linha)
    {
        return LinhaCuidadoMedicamentos::where('linha_cuidado_medicamentos.linha_cuidado', $linha)
            ->select(['linha_cuidado_medicamentos.medicamento', 'linha_cuidado_medicamentos.valor', 'linha_cuidado_medicamentos.default', 'medicamentos.nome', 'linha_cuidado_medicamentos.id', 'linha_cuidado_medicamentos.linha_cuidado'])
            ->join('medicamentos', 'medicamentos.id', '=', 'linha_cuidado_medicamentos.medicamento')
            ->get()
            ->toArray();
    }

    public static function getMedicosByLinhaCuidado($linha)
    {
        $res = Profissionais::where('profissionais_linha_cuidado.linha_cuidado', $linha)
            ->select(['profissionais.id', 'profissionais.nome'])
            ->join('profissionais_linha_cuidado', 'profissionais_linha_cuidado.profissional', '=', 'profissionais.id')
            ->where('profissionais.type', 1)
            ->where('profissionais.ativo', 1)
            ->get();

        $data = [];
        foreach ($res AS $row) {
            $data[$row->id] = $row->nome;
        }

        return $data;
    }

    public static function getLinhaDiagnostico()
    {
        $data = LinhaCuidado::select(['id', 'abreviacao', 'nome'])
            ->where('especialidade', 1)
            ->where('ativo', true)
            ->get();

        return $data;
    }

    public static function getLinhaCirurgica()
    {
        $data = LinhaCuidado::select(['id', 'abreviacao', 'nome'])
            ->where('especialidade', 2)
            ->where('ativo', true)
            ->get();

        return $data;
    }


}
