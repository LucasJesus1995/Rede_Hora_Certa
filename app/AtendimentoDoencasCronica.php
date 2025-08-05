<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoDoencasCronica extends Model
{

    protected $table = 'atendimento_doencas_cronica';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
