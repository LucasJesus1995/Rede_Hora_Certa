<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutosSolicitacoes extends Model
{
    protected $table = 'produtos_solicitacoes';

    protected $fillable = ['arena', 'produto', 'solicitante', 'responsavel', 'quantidade', 'status'];


    public static function lista($status = 'aberto', $arena = null)
    {
        if($arena){
            $whr = "produtos_solicitacoes.arena = {$arena}";
        } else {
            $whr = "1";
        }
        return self::select('produtos_solicitacoes.id', 'produtos.nome AS produto', 'arenas.nome AS arena', 
                            'users.name AS solicitante', 'produtos_solicitacoes.quantidade', 'produtos_solicitacoes.created_at')
                    ->join('arenas', 'produtos_solicitacoes.arena', '=', 'arenas.id')
                    ->join('produtos', 'produtos_solicitacoes.produto', '=', 'produtos.id')
                    ->join('users', 'produtos_solicitacoes.solicitante', '=', 'users.id')
                    ->orderBy('produtos_solicitacoes.id', 'ASC')
                    ->where('status', $status)
                    ->whereRaw($whr)
                    ->paginate(10);
    }
}
