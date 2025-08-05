<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;

class ContratoLotes extends Model
{
	protected $table = 'contrato_lotes';
    
    public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static function getLotes($contrato)
    {
        $lotes = ContratoLotes::select("lotes.*")->where("contrato_lotes.contrato", $contrato)
            ->join('lotes','lotes.id','=','contrato_lotes.lote')
            ->get();




        return $lotes;

    }


}
