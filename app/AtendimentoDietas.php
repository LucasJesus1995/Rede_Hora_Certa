<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoDietas extends Model
{

    protected $table = 'atendimento_dietas';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
