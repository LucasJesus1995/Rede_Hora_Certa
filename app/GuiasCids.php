<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuiasCids extends Model
{
    protected $table = 'guia_cids';

    public static function saveData($data)
    {
        $guia_cid = self::getByGuiasCid($data['guia'], $data['cid']);
        
        if(!$data['checked'] && !is_null($guia_cid)){
            $guia_cid->delete();
        }
        else{
            $_guia_cid = is_null($guia_cid) ? new GuiasCids() : $guia_cid;
            
            $_guia_cid->guia = $data['guia'];
            $_guia_cid->cid = $data['cid'];
            $_guia_cid->save();
        }
    }

    public static function getByGuiasCid($guia, $cid){
        $guia_cid = self::select(
            ['id']
        )
            ->where('guia', $guia)
            ->where('cid', $cid)
            ->get()
        ;
        return !empty($guia_cid[0]) ? $guia_cid[0] : null;
    }

    public static function getByGuias($guia){
        return self::where('guia', $guia)->lists('guia', 'cid')->toArray();
    }

}
