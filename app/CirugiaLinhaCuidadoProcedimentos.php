<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;

class CirugiaLinhaCuidadoProcedimentos extends Model{

    protected $table = 'cirugia_linha_cuidado_procedimentos';

    public static function boot() {
        parent::boot();

    	static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }

        });
    }

    public static function saveCirugiaProcedimento($data)
    {
        $id = self::getCirugiaProcedimentoByProcedimentoLinhaCuidado($data['linha_cuidado'], $data['procedimento']);

        $model = (empty($id)) ? new CirugiaLinhaCuidadoProcedimentos() : self::find($id);

        $model->qtd = $data['quantidade'];
        $model->procedimento = $data['procedimento'];
        $model->cirugia_linha_cuidado = $data['linha_cuidado'];
        $model->save();
    }

    protected static function getCirugiaProcedimentoByProcedimentoLinhaCuidado($linha_cuidado, $procediemnto)
    {
        $data = CirugiaLinhaCuidadoProcedimentos::select('id')
            ->where('procedimento', $procediemnto)
            ->where('cirugia_linha_cuidado', $linha_cuidado)
            ->get()
            ->toArray();

        return (!empty($data) && !empty($data[0]['id'])) ? $data[0]['id'] : null;
    }

    public static function deleteCirugiaProcedimento($data)
    {
        $id = self::getCirugiaProcedimentoByProcedimentoLinhaCuidado($data['linha_cuidado'], $data['procedimento']);

        if(!empty($id)){
            $model = self::find($id);
            $model->delete();
        }
    }
}
