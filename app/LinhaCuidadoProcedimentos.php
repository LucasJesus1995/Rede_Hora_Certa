<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;

class LinhaCuidadoProcedimentos extends Model
{
    protected $table = 'linha_cuidado_procedimentos';

    public static function boot() {
        parent::boot();

    	static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static  function ByLinhaCuidadoProcedimento($linha_cuidado, $procedimento){
        $_res =  LinhaCuidadoProcedimentos::select(
                [
                    'linha_cuidado_procedimentos.linha_cuidado',
                    'linha_cuidado_procedimentos.procedimento'
                ]
            )
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->where('linha_cuidado_procedimentos.procedimento', $procedimento)
            ->get()
            ->toArray()
        ;

        return !empty($_res[0]) ? $_res[0] : null;
    }

}
