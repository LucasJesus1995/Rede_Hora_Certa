<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Zend\Filter\Digits;

class Programas extends Model{
    protected $table = 'programas';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }

        });
    }
    
    public static function Combo(){
        return self::where('ativo',true)->orderBy('alias','asc')->lists('alias','id')->toArray();
    }

    public static function getAll()
    {
        $data = [];

        $key = 'get-all-programas';
        if (!Cache::has($key)) {
            $programas = self::select(['id','nome','alias'])->where('ativo',true)->orderBy('alias','asc')->get();

            foreach ($programas as $programas) {
                $data[$programas->id] = "{$programas->nome} - {$programas->alias}";
            }

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function get($id)
    {
        $data = [];

        $key = 'get-programas-'.$id;
        if (!Cache::has($key)) {
            $data = self::find($id);

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getNomeByprogramas($programas)
    {
        $programas = self::get($programas);

        return count($programas) && !empty($programas->nome) ? $programas->nome ." - ".$programas->alias : null;
    }

}
