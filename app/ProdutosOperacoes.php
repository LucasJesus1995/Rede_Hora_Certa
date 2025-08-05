<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutosOperacoes extends Model
{
    protected $table = 'produtos_operacoes';

    protected $fillable = ['uuid', 'solicitacao',  'produto', 'lote', 'user', 'quantidade', 'tipo_operacao', 'origem', 'arena', 'tipo_baixa', 
                            'tipo_consumo', 'recebido', 'user_recebido', 'data_recebido'];


    public static function relatorioBaixa($dados){

        $data_inicial = \App\Http\Helpers\Util::Date2DB($dados['data_inicial']) . ' 00:00:00';
        $data_final = \App\Http\Helpers\Util::Date2DB($dados['data_final']) . ' 23:59:59';

        // dd($dados);

        if(!empty($dados['arena'])){
            $whr = "produtos_operacoes.arena = {$dados['arena']}";
        } else {
            $whr = "1";
        }

        return self::select('produtos.nome AS nome_produto', 'arenas.nome AS nome_arena', 'produtos_lote.codigo AS numero_lote',
                            'produtos_operacoes.tipo_baixa', 'produtos_operacoes.tipo_consumo', 'users.name AS usuario',
                            'produtos_operacoes.quantidade', 'produtos_operacoes.created_at AS data_baixa')
                            ->join('produtos', 'produtos_operacoes.produto', '=', 'produtos.id')
                            ->join('arenas', 'produtos_operacoes.arena', '=', 'arenas.id')
                            ->join('produtos_lote', 'produtos_operacoes.lote', '=', 'produtos_lote.id')
                            ->join('users', 'produtos_operacoes.user', '=', 'users.id')
                            ->where('produtos_operacoes.tipo_operacao', 'baixa')
                            ->where('produtos_operacoes.created_at', '>=', $data_inicial)
                            ->where('produtos_operacoes.created_at', '<=', $data_final)
                            ->whereRaw($whr)
                            ->get()
                            ->toArray();
    }

    public static function relatorioTransferencias($dados)
    {
        $data_inicial = \App\Http\Helpers\Util::Date2DB($dados['data_inicial']) . ' 00:00:00';
        $data_final = \App\Http\Helpers\Util::Date2DB($dados['data_final']) . ' 23:59:59';

        if(!empty($dados['arena'])){
            $whr = "produtos_operacoes.arena = {$dados['arena']}";
        } else {
            $whr = "1";
        }

        return self::select('produtos.nome AS nome_produto', 'arenas.nome AS nome_arena', 'produtos_lote.codigo AS numero_lote',
                            'users.name AS usuario', 'produtos_operacoes.quantidade', 'produtos_operacoes.created_at AS data_transferencia',
                            'users2.name AS usuario_recebido', 'produtos_operacoes.data_recebido')
                            ->join('produtos', 'produtos_operacoes.produto', '=', 'produtos.id')
                            ->join('arenas', 'produtos_operacoes.arena', '=', 'arenas.id')
                            ->join('produtos_lote', 'produtos_operacoes.lote', '=', 'produtos_lote.id')
                            ->join('users', 'produtos_operacoes.user', '=', 'users.id')
                            ->leftjoin('users AS users2', 'produtos_operacoes.user_recebido', '=', 'users2.id')
                            ->where('produtos_operacoes.tipo_operacao', 'transferencia')
                            ->where('produtos_operacoes.created_at', '>=', $data_inicial)
                            ->where('produtos_operacoes.created_at', '<=', $data_final)
                            ->whereRaw($whr)
                            ->get()
                            ->toArray();
    }

    public static function relatorioEntrada($dados)
    {
        $data_inicial = \App\Http\Helpers\Util::Date2DB($dados['data_inicial']) . ' 00:00:00';
        $data_final = \App\Http\Helpers\Util::Date2DB($dados['data_final']) . ' 23:59:59';

        return self::select('produtos.nome AS nome_produto', 'produtos_lote.codigo AS numero_lote', 'users.name AS usuario',
                            'produtos_operacoes.quantidade', 'produtos_operacoes.created_at AS data_entrada')
                            ->join('produtos', 'produtos_operacoes.produto', '=', 'produtos.id')
                            ->join('produtos_lote', 'produtos_operacoes.lote', '=', 'produtos_lote.id')
                            ->join('users', 'produtos_operacoes.user', '=', 'users.id')
                            ->where('produtos_operacoes.tipo_operacao', 'adicao')
                            ->where('produtos_operacoes.created_at', '>=', $data_inicial)
                            ->where('produtos_operacoes.created_at', '<=', $data_final)
                            ->get()
                            ->toArray();
    }
}
