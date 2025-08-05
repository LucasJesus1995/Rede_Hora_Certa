<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoMedicacaoRegularmente extends Model
{

    protected $table = 'atendimento_medicacao_regularmente';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
