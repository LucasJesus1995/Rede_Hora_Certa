<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgendaEtapas extends Model
{

    protected $table = 'agenda_etapas';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {


        });

    }


}
