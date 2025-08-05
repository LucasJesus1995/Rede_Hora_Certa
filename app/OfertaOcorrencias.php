<?php

namespace App;

use App\Http\Helpers\Util;
use App\Traits\ModelTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfertaOcorrencias extends Model
{
    use ModelTraits;

    protected $table = 'oferta_ocorrencias';

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
