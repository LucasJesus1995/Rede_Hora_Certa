<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;

class Guias extends Model
{
    protected $table = 'guias';

    public static function boot() {
        parent::boot();

    	static::saving(function($model) {
            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }	
}
