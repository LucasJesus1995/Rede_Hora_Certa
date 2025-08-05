<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoVacinacao extends Model
{

    protected $table = 'atendimento_vacinacao';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
