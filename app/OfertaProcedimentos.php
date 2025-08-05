<?php

namespace App;

use App\Http\Helpers\Util;
use App\Traits\ModelTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfertaProcedimentos extends Model
{
    use ModelTraits;

    protected $table = 'oferta_procedimentos';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

        });
    }

    protected function procedimentos(){
        return $this->hasOne(Procedimentos::class, 'id', 'procedimento');
    }


}
