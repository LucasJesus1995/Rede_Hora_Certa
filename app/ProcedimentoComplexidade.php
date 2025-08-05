<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Http\Helpers\Util;

class ProcedimentoComplexidade extends Model{
    protected $table = 'procedimento_complexidade';


    public static function Combo(){
        $key = 'procedimento-complexidade';

        if (!Cache::has($key)) {
            $data = [];
            foreach (self::all() as $row) {
                $data[$row->id] = $row->sigla." - ".$row->nome;
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
        $key = 'get-complexidade-' . $id;

        if (!Cache::has($key)) {
            $data = self::find($id);

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getSigla($complexidade)
    {
        $data = self::get($complexidade);

        return !empty($data->sigla) ? $data->sigla : null;
    }

}
