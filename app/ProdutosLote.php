<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutosLote extends Model
{
    protected $table = 'produtos_lote';

    protected $fillable = ['produto', 'codigo', 'vencimento', 'fornecedor', 'fabricante', 'nf'];

    public static function relatorioVencimento($dados)
    {

        if($dados['vencimento'] == 'vencidos'){
            $whr = 'produtos_lote.vencimento < ' . "'" . date('Y-m-d') . "'";
        } else {
            $whr = "(produtos_lote.vencimento BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d', strtotime('+' . $dados['vencimento'] . ' days', strtotime(date('Y-m-d')))) . "')";
        }

        if(!empty($dados['arena'])){
            $whr2 = "produtos_quantidade.arena = {$dados['arena']}";
        } else {
            $whr2 = "1";
        }

        // dd($dados);

        return self::select('produtos.nome AS nome_produto', 'produtos_lote.codigo', 'arenas.nome AS nome_arena', 'produtos_quantidade.quantidade', 'produtos_lote.vencimento')
                        ->join('produtos', 'produtos_lote.produto', '=', 'produtos.id')
                        ->join('produtos_quantidade',  'produtos_lote.id', '=', 'produtos_quantidade.lote')
                        ->leftjoin('arenas', 'produtos_quantidade.arena', '=', 'arenas.id')
                        ->where('produtos_quantidade.quantidade', '>', 0)
                        ->whereRaw($whr)
                        ->whereRaw($whr2)
                        ->get()
                        ->toArray();

                    
    }
}
