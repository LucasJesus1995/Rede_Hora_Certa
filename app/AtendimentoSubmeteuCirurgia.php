<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoSubmeteuCirurgia extends Model
{

    protected $table = 'atendimento_submeteu_cirurgia';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
