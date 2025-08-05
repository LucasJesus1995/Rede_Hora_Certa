<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\Cache;

class Exames extends Model
{
    protected $table = 'exames';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static function Combo(){
        $data =  self::where('ativo', true)->orderBy('nome','asc')->lists('nome','id')->toArray();

        return $data;
    }

    public static function get($id){
        $key = 'get-exames-'.$id;

        if (!Cache::has($key)) {
            $data = self::find($id)->toArray();

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

}
