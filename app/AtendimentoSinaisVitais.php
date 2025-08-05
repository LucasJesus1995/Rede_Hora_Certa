<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoSinaisVitais extends Model
{

    protected $table = 'atendimento_sinais_vitais';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
