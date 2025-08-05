<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoAtividadeFisica extends Model
{

    protected $table = 'atendimento_atividade_fisica';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
