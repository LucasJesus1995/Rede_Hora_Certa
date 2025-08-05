<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoSexo extends Model
{

    protected $table = 'atendimento_sexo';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
