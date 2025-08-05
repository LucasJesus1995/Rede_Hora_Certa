<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoQueixas extends Model
{

    protected $table = 'atendimento_queixas';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
