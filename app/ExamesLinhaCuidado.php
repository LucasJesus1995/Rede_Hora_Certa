<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\Cache;

class ExamesLinhaCuidado extends Model
{
    protected $table = 'exames_linha_cuidado';

    public static function get($id)
    {
        $key = 'get-exames_linha_cuidado-' . $id;

        if (!Cache::has($key)) {
            $data = self::find($id)->toArray();

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getLinhaCuidado($exame)
    {
        $sql = LinhaCuidado::select('linha_cuidado.nome', 'linha_cuidado.id', 'exames_linha_cuidado.id AS exames_linha_cuidado_id')
            ->leftjoin('exames_linha_cuidado', function ($join) use ($exame) {
                $join->on('exames_linha_cuidado.linha_cuidado', '=', 'linha_cuidado.id')
                    ->where('exames_linha_cuidado.exame', '=', $exame);
            })
            ->orderBy('linha_cuidado.nome', 'asc')
            ->where('ativo', true);

        return $sql->get();
    }

    public static function getByLinhaCuidadoExame($exame, $linha_cuidado)
    {
        $data = self::where('exame', $exame)->where('linha_cuidado', $linha_cuidado)->get();

        return !empty($data[0]) ? $data[0] : null;
    }

    public static function saveLinhaCuidadoExame($exame, $linha_cuidado, $acao)
    {
        $exame_linha_cuidado = self::getByLinhaCuidadoExame($exame, $linha_cuidado);

        if ($acao == 0 && !is_null($exame_linha_cuidado)) {
            $exame_linha_cuidado->delete();
        } else {
            $exame_linha_cuidado = !is_null($exame_linha_cuidado) ? $exame_linha_cuidado : new ExamesLinhaCuidado();
            $exame_linha_cuidado->exame = $exame;
            $exame_linha_cuidado->linha_cuidado = $linha_cuidado;
            $exame_linha_cuidado->save();
        }

        return $exame_linha_cuidado;
    }

}
