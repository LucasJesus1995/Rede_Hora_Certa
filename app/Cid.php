<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Cid extends Model{
    protected $table = 'cid';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }

        });
    }
    
    public static function Combo(){
        return self::getAll();
    }

    public static function getAll()
    {
        $data = [];

        $key = 'get-all-cids';
        if (!Cache::has($key)) {
            $cids = self::select(['id','codigo','descricao'])->where('ativo',true)->orderBy('descricao','asc')->get();

            foreach ($cids as $cid) {
                $data[$cid->id] = "{$cid->codigo} - {$cid->descricao}";
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

        $key = 'get-cid-'.$id;
        if (!Cache::has($key)) {
            $data = self::find($id);

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);
        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getNomeByCid($cid)
    {
        $cid = self::get($cid);

        return count($cid) && !empty($cid->codigo) ? $cid->codigo ." - ".$cid->descricao : null;
    }

}
