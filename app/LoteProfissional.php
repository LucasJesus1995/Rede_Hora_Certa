<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoteProfissional extends Model
{
    protected $table = 'lote_profissionais';

    public static function getLoteProfissional($lote, $profissional){
        $data = LoteProfissional::where('lote', $lote)->where('profissional',$profissional)->get();

        return $data->count() ? $data[0] : null;
    }

    public static function saveData($lote, $profissional)
    {
        $data = new LoteProfissional();
        $data->lote = $lote;
        $data->profissional = $profissional;
        $data->save();

        return $data;
    }

    public static function getByLote($lote){
        $data = LoteProfissional::select([
                    'profissionais.id AS profissionais_id',
                    'profissionais.cns AS profissionais_cns',
                    'profissionais.nome AS profissionais_nome',
                    'lote_profissionais.id AS lote_profissionais_id',
                ]
            )
            ->where('lote', $lote)
            ->join('profissionais','profissionais.id','=','lote_profissionais.profissional')
            ->orderBy('profissionais.nome','asc')
            ->get();

        return $data->count() ? $data: null;
    }

    public static function getLoteByProfissional($profissional) {
        $data = LoteProfissional::join('profissionais', 'profissionais.id', '=', 'lote_profissionais.profissional')
            ->join('lotes', 'lotes.id', '=', 'lote_profissionais.lote')
            ->where('profissional', $profissional)
            ->get();

        return !empty($data) ? $data : array();
    }
}
