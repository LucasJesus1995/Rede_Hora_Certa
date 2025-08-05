<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Zend\Filter\Digits;

class Condutas extends Model{
    protected $table = 'condutas';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }

        });
    }
    
    public static function Combo(){
        return self::where('ativo',true)->orderBy('nome','asc')->lists('nome','id')->toArray();
    }

    public static function ComboEspecialidadeTipoAtendimento($especialidade, $tipo_atendimento, $regulacao = 0){
        return self::where('ativo',true)->where('especialidade', $especialidade)->where('regulacao', $regulacao)->where('tipo_atendimento', $tipo_atendimento)->orderBy('nome','asc')->lists('nome','id')->toArray();
    }

    public static function getAll()
    {
        $data = [];

        $key = 'get-all-condutas';
        if (!Cache::has($key)) {
            $programas = self::select(['id','nome'])->where('ativo',true)->orderBy('nome','asc')->get();

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

        $key = 'get-tipo-condutas-'.$id;
        if (!Cache::has($key)) {
            $data = self::find($id);

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

}
