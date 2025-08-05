<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;

class Estabelecimento extends Model
{
    protected $table = 'estabelecimento';


    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->getAttributes() as $key => $value) {
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static function Combo()
    {
        return self::lists('nome', 'id')->toArray();
    }

    public static function getEstabelecimentoSaveByNome($nome)
    {
        $_estabelecimento = Estabelecimento::where('nome', trim($nome))->get();

        if (empty($_estabelecimento[0])) {
            $_model = new Estabelecimento();
            $_model->nome = trim($nome);
            $_model->save();

            $estabelecimento = $_model;
        } else {
            $estabelecimento = $_estabelecimento[0];
        }

        return !empty($estabelecimento) ? $estabelecimento : null;
    }
}
