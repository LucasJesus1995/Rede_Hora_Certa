<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoAcuidadeVisual extends Model
{

    protected $table = 'atendimento_acuidade_visual';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
