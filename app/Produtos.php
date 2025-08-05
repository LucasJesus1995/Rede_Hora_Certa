<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    protected $table = 'produtos';

    protected $fillable = ['user', 'categoria', 'nome', 'codigo', 'descricao', 'unidade_medida', 'tipo_apresentacao', 'ativo'];

    public static function Combo()
    {
        return Produtos::lists('nome', 'id')->toArray();
    }

    public static function ComboQuantidade() // retorna os produtos do estoque central que a quantidade seja maior que 0 (zero)
    {
        $produtos =  Produtos::select('produtos.nome', 'produtos.id', 'produtos_quantidade.quantidade')
                        ->join('produtos_quantidade', 'produtos.id', '=', 'produtos_quantidade.produto')
                        ->where('produtos_quantidade.central', 1)
                        ->where('produtos_quantidade.quantidade', '>', 0)
                        ->get();
        $array_produtos = array();

        foreach($produtos as $produto){
            $array_produtos[$produto->id] = "$produto->nome";
        }

        return $array_produtos;
    }

    
}
