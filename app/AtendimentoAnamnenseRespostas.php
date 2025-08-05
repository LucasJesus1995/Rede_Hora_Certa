<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Zend\Filter\Digits;

class AtendimentoAnamnenseRespostas extends Model{
    protected $table = 'atendimento_anamnense_respostas';

    public static function boot() {
        parent::boot();

        static::saving(function($model) {

            foreach($model->getAttributes() AS $key => $value){
                $model->$key = Util::String2DB($value);
            }

        });
    }
}
