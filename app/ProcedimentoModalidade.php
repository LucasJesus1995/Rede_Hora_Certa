<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Http\Helpers\Util;

class ProcedimentoModalidade extends Model
{
    protected $table = 'procedimento_modalidades';

    public static function Combo()
    {
        $key = 'procedimento-modalidades';

        if (!Cache::has($key)) {
            $data = [];
            foreach (self::all() as $row) {
                $data[$row->id] = $row->sigla . " - " . $row->nome;
            }

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function get($id)
    {
        $key = 'get-modalidade-' . $id;

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

    public static function getSigla($modalidade)
    {
        $data = self::get($modalidade);

        return !empty($data->sigla) ? $data->sigla : null;
    }

}
