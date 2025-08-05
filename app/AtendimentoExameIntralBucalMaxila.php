<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoExameIntralBucalMaxila extends Model
{

    protected $table = 'atendimento_exames_intra_bucal_maxila';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
