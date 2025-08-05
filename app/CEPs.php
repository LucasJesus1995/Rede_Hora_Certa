<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CEPs extends Model
{
    protected $table = 'ceps';

    public static function ValidaCEPExportacao($cep = null)
    {
        $cep = Util::StrPadLeft(intval($cep), 8, 0);

        if ($cep == "00000000") {
            $cep = "04276000";
        } else {
            $_cep = self::isCEP($cep);
            if (!$_cep) {
                $cep = "04276000";
            } else {
                $cep = $_cep;
            }
        }

        return (!is_null($cep) || intval($cep) > 0) ? $cep : "04276000";
    }

    public static function isCEP($cep)
    {
        $data = null;

        $key = 'get-isCEP-' . $cep;
        if (!Cache::has($key)) {
            $cep = self::select(['cep'])->where('cep', $cep)->get();

            if (!empty($cep[0]) && !empty($cep[0]->cep)) {
                $data = $cep[0]->cep;

                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getByCEP($cep)
    {
        $data = null;

        $key = 'get-getByCEP-' . $cep;
        if (!Cache::has($key)) {
            $cep = self::where('cep', $cep)->get();

            if (!empty($cep[0]) && !empty($cep[0]->cep)) {
                $data = $cep[0];

                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }


}
