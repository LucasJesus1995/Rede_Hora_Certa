<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinhaCuidadoCids extends Model
{
    protected $table = 'linha_cuidado_cid';

    public static function saveData($data)
    {
        $linha_cuidado_cid = self::getByLinhaCuidadoCid($data['linha_cuidado'], $data['cid']);
        
        if(!$data['checked'] && !is_null($linha_cuidado_cid)){
            $linha_cuidado_cid->delete();
        }
        else{
            $_linha_cuidado_cid = is_null($linha_cuidado_cid) ? new LinhaCuidadoCids() : $linha_cuidado_cid;
            
            $_linha_cuidado_cid->linha_cuidado = $data['linha_cuidado'];
            $_linha_cuidado_cid->cid = $data['cid'];
            $_linha_cuidado_cid->save();
        }
    }

    public static function getByLinhaCuidadoCid($linha_cuidado, $cid){
        $linha_cuidado_cid = self::select(
            ['id']
        )
            ->where('linha_cuidado', $linha_cuidado)
            ->where('cid', $cid)
            ->get()
        ;
        return !empty($linha_cuidado_cid[0]) ? $linha_cuidado_cid[0] : null;
    }

    public static function getByLinhaCuidado($linha_cuidado){
        return self::where('linha_cuidado', $linha_cuidado)->lists('linha_cuidado', 'cid')->toArray();
    }

}
