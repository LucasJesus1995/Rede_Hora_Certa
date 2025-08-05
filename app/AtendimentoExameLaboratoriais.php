<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoExameLaboratoriais extends Model
{

    protected $table = 'atendimento_exames_laboratoriais';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
