<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoAuxiliar extends Model
{

    protected $table = 'atendimento_auxiliar';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }

    public static function getByAtendimento($atendimento)
    {
        $data = self::where('atendimento', $atendimento)->get();

        return !empty($data[0]) ? $data[0] : null;
    }


}
