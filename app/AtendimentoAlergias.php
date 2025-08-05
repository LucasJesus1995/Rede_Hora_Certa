<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoAlergias extends Model
{

    protected $table = 'atendimento_alergias';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
