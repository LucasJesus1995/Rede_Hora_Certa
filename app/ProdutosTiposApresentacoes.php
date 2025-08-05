<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutosTiposApresentacoes extends Model
{
    protected $table = 'produtos_tipos_apresentacoes';

    public static function Combo()
    {
        return self::lists('nome', 'id')->toArray();
    }
}
