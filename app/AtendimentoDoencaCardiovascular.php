<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoDoencaCardiovascular extends Model
{

    protected $table = 'atendimento_doenca_cardiovascular';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
