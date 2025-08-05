<?php

namespace App;

use App\Http\Helpers\Util;
use App\Traits\ModelTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfertaStatus extends Model
{
    use ModelTraits;

    protected $table = 'oferta_status';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

            if(empty($model->id)){
                $model->user = Auth::user()->id;
            }


        });
    }


}
