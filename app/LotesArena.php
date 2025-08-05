<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\Cache;

class LotesArena extends Model{

    protected $table = 'lotes_arena';
    
    public static function boot() {
        parent::boot();

    	static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
        Cache::flush();
    }

    public static  function Combo(){
        return self::lists('nome','id')->toArray();
    }

    public static function getArenaLote($lote, $arena)
    {
        return LotesArena::where(array('arena'=>$arena, 'lote' => $lote))->get()->toArray();
    }

    public static function getArenasByLote($lote)
    {
        $lote =  LotesArena::where(array('lote' => $lote))->get();

        return $lote;
    }

}
