<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutosFabricantes extends Model
{
    protected $table = 'produtos_fabricantes';

    protected $fillable = ['razao_social', 'nome_fantasia', 'cnpj', 'endereco', 'cidade', 'uf', 'cep', 'email', 'telefone'];

    public static function lista()
    {
        return self::select('id', 'razao_social', 'nome_fantasia')->paginate(15);
    }

    public static function Combo()
    {
        return self::orderBy('nome')->lists('razao_social AS nome', 'id')->toArray();
    }
}

