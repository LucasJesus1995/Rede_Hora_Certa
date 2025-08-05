<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Http\Helpers\Util;

class Cidades extends Model{
    protected $table = 'cidades';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {
            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }   

    public static function Combo($estado){
        $key = 'cidade-combo-estado-'.$estado;

        if (!Cache::has($key)) {
            $data = [];
            foreach (Cidades::where('estado',$estado)->get() as $row) {
                $data[$row->id] = $row->ibge." - ".$row->nome;
            }

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);

        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function get($id){
        $key = 'cidades-'.$id;

        if (!Cache::has($key)) {
            $data = Cidades::find($id);

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);

        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getByIbge($ibge)
    {
        $key = 'get-by-ibge-'.$ibge;
        $data = null;

        if (!Cache::has($key)) {
            $_data = Cidades::where('ibge', $ibge)->get();

            if (count($_data)) {
                $data = $_data[0];
                Cache::put($key, $data, CACHE_WEEK);
            }

        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getByIbgeLike($ibge)
    {
        $key = 'get-by-ibge-like-'.$ibge;
        $data = null;

        if (!Cache::has($key)) {
            $_data = Cidades::where('ibge','LIKE', "{$ibge}%")->get();

            if (count($_data)) {
                $data = $_data[0];
                Cache::put($key, $data, CACHE_WEEK);
            }

        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

}
