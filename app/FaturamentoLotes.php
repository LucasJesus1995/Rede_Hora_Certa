<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Zend\Filter\Digits;

class FaturamentoLotes extends Model
{
    protected $table = 'faturamento_lotes';

    public static function saveData($faturamento, $lote)
    {
        $faturamento_lote = self::getFaturamentLoteByFaturamentoLote($faturamento, $lote);
        if(empty($faturamento_lote)){
            $faturamento_lote = new FaturamentoLotes();
            $faturamento_lote->faturamento = $faturamento;
            $faturamento_lote->lote = $lote;
            $faturamento_lote->save();
        }

        return $faturamento_lote;
    }

    public static function getFaturamentLoteByFaturamentoLote($faturamento, $lote){
        $faturamento_lote = self::where("faturamento",$faturamento)->where('lote', $lote)->get();

        return count($faturamento_lote) ? $faturamento_lote[0] : null;
    }

    public static function getFaturamentLoteByFaturamento($faturamento){

        $faturamento_lote = self::select(
                [
                    'faturamento_lotes.id AS faturamento_lote',
                    'lotes.id',
                    'lotes.nome',
                ]
            )
            ->where("faturamento","=",$faturamento)
            ->where("lotes.ativo","=",1)
            ->join('lotes','lotes.id', '=', 'faturamento_lotes.lote')
            ->get();

        return count($faturamento_lote) ? $faturamento_lote : null;
    }




}
