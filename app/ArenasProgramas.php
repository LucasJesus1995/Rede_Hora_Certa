<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArenasProgramas extends Model
{
    protected $table = 'programa_arenas';

    public static function saveData($data)
    {
        $arenas_programa = self::getByArenasPrograma($data['arena'], $data['programa']);

        if(!$data['checked'] && !is_null($arenas_programa)){
            $arenas_programa->delete();
        }
        else{
            $_arenas_programa = is_null($arenas_programa) ? new ArenasProgramas() : $arenas_programa;

            $_arenas_programa->arena = $data['arena'];
            $_arenas_programa->programa = $data['programa'];
            $_arenas_programa->save();
        }
    }

    public static function getByArenasPrograma($arenas, $programa){
        $arenas_programa = self::select(
            ['id']
        )
            ->where('arena','=',$arenas)
            ->where('programa','=',$programa)
            ->get()
        ;
        return !empty($arenas_programa[0]) ? $arenas_programa[0] : null;
    }

    public static function getByPrograma($programa){
        return self::where('programa',$programa)->lists('programa', 'arena')->toArray();
    }

}
