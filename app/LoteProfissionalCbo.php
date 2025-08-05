<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoteProfissionalCbo extends Model
{
    protected $table = 'lote_profissional_cbos';

    public static function saveData($lote_profissional, $cbo)
    {
        $data = new LoteProfissionalCbo();
        $data->cbo = $cbo;
        $data->lote_profissional = $lote_profissional;
        $data->save();

        return $data;
    }

    public static function getLoteProfissionalCbo($lote_profissional, $cbo){
        $data = LoteProfissionalCbo::where('lote_profissional', $lote_profissional)->where('cbo',$cbo)->get();

        return $data->count() ? $data[0] : null;
    }

    public static function getLoteProfissionalCbo2($lote, $profissional, $codigo)
    {

        $lote_profissional = LoteProfissional::where('lote', $lote)->where('profissional', $profissional)->get();
        if ($lote_profissional->count()) {
            $lote_profissional_cbo = self::getLoteProfissionalCbo($lote_profissional[0]->id, $codigo);

            if (!empty($lote_profissional_cbo)) {
                return $lote_profissional_cbo;
            }
        }

        return false;
    }

    public static function getCbos($lote_profissionais_id)
    {

        $data = LoteProfissionalCbo::select(['cbo.codigo','cbo.nome'])
            ->join('cbo','cbo.id','=','lote_profissional_cbos.cbo')
            ->where('lote_profissional', $lote_profissionais_id)
            ->get();

        return $data->count() ? $data : null;
    }

}
