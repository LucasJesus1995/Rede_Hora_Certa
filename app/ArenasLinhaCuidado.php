<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;

class ArenasLinhaCuidado extends Model
{
    protected $table = 'arenas_linha_cuidado';

    public static function boot() {
        parent::boot();

    	static::saving(function($model) {
            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }
}
