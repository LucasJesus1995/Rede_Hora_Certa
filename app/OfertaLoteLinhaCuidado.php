<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OfertaLoteLinhaCuidado extends Model
{

    protected $table = 'oferta_lote_linha_cuidado';

    public static function boot()
    {
        parent::boot();
    }

    public static function saveData($data)
    {
        $lote = $data['lote'];
        $ano = $data['ano'];
        $mes = $data['mes'];
        $arena = $data['arena'];
        $linha_cuidado = $data['linha_cuidado'];
        $qtd = intval($data['qtd']);

        $arena_lote_linha_cuidado = self::getOfertaArenaLoteLinhaCuidado($lote, $arena, $linha_cuidado, $mes, $ano);
        $_arena_lote_linha_cuidado = ($arena_lote_linha_cuidado) ? $arena_lote_linha_cuidado : new OfertaLoteLinhaCuidado();
        if ($qtd > 0) {
            $_arena_lote_linha_cuidado->lote = $lote;
            $_arena_lote_linha_cuidado->ano = $ano;
            $_arena_lote_linha_cuidado->mes = $mes;
            $_arena_lote_linha_cuidado->arena = $arena;
            $_arena_lote_linha_cuidado->linha_cuidado = $linha_cuidado;
            $_arena_lote_linha_cuidado->qtd = $qtd;
            $_arena_lote_linha_cuidado->save();
        } else {
            if (!is_null($_arena_lote_linha_cuidado)) {
                $_arena_lote_linha_cuidado->delete();
            }
        }

        return true;
    }

    public static function getOfertaArenaLoteLinhaCuidado($lote, $arena, $linha_cuidado, $mes, $ano)
    {
        $data = self::where('lote', $lote)
            ->where('arena', $arena)
            ->where('linha_cuidado', $linha_cuidado)
            ->where('mes', $mes)
            ->where('ano', $ano)
            ->get();

        return !empty($data[0]) ? $data[0] : null;
    }

    public static function getByLoteAnoMes($lote, $ano, $mes, $arena = null, $linha_cuidado = null)
    {
        $data = self::where('lote', $lote)
            ->where('mes', $mes)
            ->where('ano', $ano);

        if ($arena) {
            $data->where('arena', $arena);
        }

        if ($linha_cuidado) {
            $data->where('linha_cuidado', $linha_cuidado);
        }

        return $data->get();
    }


}
