<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoHistoricoFamiliar extends Model
{

    protected $table = 'atendimento_historico_familiar';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
