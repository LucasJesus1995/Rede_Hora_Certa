<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcedimentoCids extends Model
{
    protected $table = 'procedimento_cids';

    public static function saveData($data)
    {
        $procedimento_cid = self::getByProcedimentoCid($data['procedimento'], $data['cid']);
        
        if(!$data['checked'] && !is_null($procedimento_cid)){
            $procedimento_cid->delete();
        }
        else{
            $_procedimento_cid = is_null($procedimento_cid) ? new ProcedimentoCids() : $procedimento_cid;
            
            $_procedimento_cid->procedimento = $data['procedimento'];
            $_procedimento_cid->cid = $data['cid'];
            $_procedimento_cid->save();
        }
    }

    public static function getByProcedimentoCid($procedimento, $cid){
        $procedimento_cid = self::select(
            ['id']
        )
            ->where('procedimento', $procedimento)
            ->where('cid', $cid)
            ->get()
        ;
        return !empty($procedimento_cid[0]) ? $procedimento_cid[0] : null;
    }

    public static function getByProcedimento($procedimento){
        return self::where('procedimento', $procedimento)->lists('procedimento', 'cid')->toArray();
    }

}
