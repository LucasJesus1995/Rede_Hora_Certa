<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinhaCuidadoProgramas extends Model
{
    protected $table = 'linha_cuidado_programas';

    public static function saveData($data)
    {
        $linha_cuidado_programa = self::getByLinhaCuidadoPrograma($data['linha_cuidado'], $data['programa']);
        
        if(!$data['checked'] && !is_null($linha_cuidado_programa)){
            $linha_cuidado_programa->delete();
        }
        else{
            $_linha_cuidado_programa = is_null($linha_cuidado_programa) ? new LinhaCuidadoProgramas() : $linha_cuidado_programa;
            
            $_linha_cuidado_programa->linha_cuidado = $data['linha_cuidado'];
            $_linha_cuidado_programa->programa = $data['programa'];
            $_linha_cuidado_programa->save();
        }
    }

    public static function getByLinhaCuidadoPrograma($linha_cuidado, $programa){
        $linha_cuidado_programa = self::select(
            ['id']
        )
            ->where('linha_cuidado','=',$linha_cuidado)
            ->where('programa','=',$programa)
            ->get()
        ;
        return !empty($linha_cuidado_programa[0]) ? $linha_cuidado_programa[0] : null;
    }

    public static function getByPrograma($programa){
        return self::where('programa',$programa)->lists('programa', 'linha_cuidado')->toArray();
    }

}
