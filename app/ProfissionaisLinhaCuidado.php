<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;

class ProfissionaisLinhaCuidado extends Model
{
 protected $table = 'profissionais_linha_cuidado';

    public static function boot() {
        parent::boot();

    	static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static function getLinhasCuidadoByProfissional($profissional) {
        $data = ProfissionaisLinhaCuidado::join('linha_cuidado', 'linha_cuidado.id', '=', 'profissionais_linha_cuidado.linha_cuidado')
            ->where('profissional', $profissional)
            ->get();

        return !empty($data) ? $data : array();
    }
}
