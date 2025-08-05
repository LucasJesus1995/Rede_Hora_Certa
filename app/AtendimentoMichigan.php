<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoMichigan extends Model
{

    protected $table = 'atendimento_michigan';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
