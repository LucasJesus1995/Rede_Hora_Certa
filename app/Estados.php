<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Http\Helpers\Util;

class Estados extends Model{
    protected $table = 'estados';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static function Combo(){
        $key = 'estados-combo';

        if (!Cache::has($key)) {
            $data = [];
            foreach (Estados::all() as $row) {
                $data[$row->id] = $row->sigla." - ".$row->nome;
            }

            if (count($data))
                Cache::put($key, $data, CACHE_DAY);

        }else{
            $data = Cache::get($key);
        }

        return $data;
    }

}
