<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Zend\Filter\Digits;

class Cbo extends Model{
    protected $table = 'cbo';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }

        });
    }
    
    public static function Combo(){
        return self::orderBy('nome','asc')->lists('nome','id')->toArray();
    }

}
