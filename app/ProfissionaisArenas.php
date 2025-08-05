<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;

class ProfissionaisArenas extends Model
{
    protected $table = 'profissionais_arenas';

    public static function boot() {
        parent::boot();

    	static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static function getArenasByProfissional($profissional) {
        $data = ProfissionaisArenas::join('arenas', 'arenas.id', '=', 'profissionais_arenas.arena')
            ->where('profissional', $profissional)
            ->get();

        return !empty($data) ? $data : array();
    }
}
