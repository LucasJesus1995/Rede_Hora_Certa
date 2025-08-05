<?php
/**
 * Created by PhpStorm.
 * User: felipe
 * Date: 04/09/18
 * Time: 17:20
 */

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;


class Organizacao extends Model{
    protected $table = 'procedimento_organizacao';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }

        });
    }

    public static function Combo(){
        return self::orderBy('descricao','asc')->lists('descricao','id')->toArray();
    }

}
