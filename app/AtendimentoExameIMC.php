<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoExameIMC extends Model
{

    protected $table = 'atendimento_exame_imc';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
