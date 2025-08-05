<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutosCategorias extends Model
{
    protected $table = 'produtos_categorias';

    protected $fillable = ['nome'];

    public static function Combo()
    {
        return ProdutosCategorias::orderBy('nome')->lists('nome', 'id')->toArray();
    }
}
