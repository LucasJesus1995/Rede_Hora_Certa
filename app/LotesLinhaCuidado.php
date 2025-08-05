<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;

class LotesLinhaCuidado extends Model{

    protected $table = 'lotes_linha_cuidado';
    
    public static function boot() {
        parent::boot();

    	static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static  function Combo(){
        return self::lists('nome','id')->toArray();
    }

    public static function getLoteLinhaCuidadoFaturamento($lote, $linha_cuidado, $faturamento)
    {
        $lote_linha_cuidado = null;
        $faturamento_lote = FaturamentoLotes::getFaturamentLoteByFaturamentoLote($faturamento, $lote);

        if($faturamento_lote){
            $lote_linha_cuidado = LotesLinhaCuidado::where('linha_cuidado',$linha_cuidado)
                ->where('faturamento_lote', $faturamento_lote->id)
                ->get()
                ->toArray();
        }

        return $lote_linha_cuidado;
    }

    public static function getByFaturamentoLoteLinhaCuidado($faturamento_lote, $linha_cuidado)
    {
        $lotes_faturamento = LotesLinhaCuidado::where(array('linha_cuidado'=>$linha_cuidado, 'faturamento_lote' => $faturamento_lote))->get();
        return count($lotes_faturamento) ? $lotes_faturamento[0] : null;
    }

    public static function getLinhaCuidadoByFaturamentoLote($faturamento_lote)
    {
        return LotesLinhaCuidado::where('faturamento_lote', $faturamento_lote)->get();
    }



}
