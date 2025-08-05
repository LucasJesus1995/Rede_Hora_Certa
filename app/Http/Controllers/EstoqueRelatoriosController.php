<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Arenas;
use App\ProdutosFornecedores;
use App\ProdutosLote;
use App\ProdutosOperacoes;
use App\ProdutosQuantidade;


class EstoqueRelatoriosController extends Controller
{
    public function baixa()
    {
        $_arenas = Arenas::select('id', 'nome')->whereIn('id', ['1', '2', '13', '3', '4'])->get();
        $arenas = array();

        foreach($_arenas as $arena){
            $arenas[$arena->id] = $arena->nome;
        } 

        return view('admin.estoque.relatorios.baixa', compact('arenas'));
    }

    public function baixaExcel(Request $request){

        $tipo_baixa = array(
            'CO' => 'Consumo',
            'PV' => 'Perda por validade',
            'AV' => 'Avaria',
        );

        $tipo_consumo = array(
            ''   => '',
            'CC' => 'Centro cirurgico',
            'AC' => 'Acolhimento',
            'CP' => 'Carro de parada',
            'ED' => 'EDA/Colono',
        );

        $dados = $request->all();

        $baixas = ProdutosOperacoes::relatorioBaixa($dados);

        if(isset($dados['tela'])){

            // dd($dados);
            
            return view('admin.estoque.relatorios.ajax-baixa', compact('baixas', 'tipo_baixa', 'tipo_consumo'));

        }

        $linhas = array();

        $linhas[] = "Produto;Arena;Lote;Tipo baixa;Tipo consumo;Responsavel;Quantidade;Data Hora";        

        foreach($baixas as $baixa){
            $baixa['tipo_baixa'] = $tipo_baixa[$baixa['tipo_baixa']];
            $baixa['tipo_consumo'] = $tipo_consumo[$baixa['tipo_consumo']];
            $linhas[] = implode(';', $baixa);
        }

        $path = PATH_FILE_RELATORIO . 'excel/baixas/' . Util::getUser() . '/';
        // $path = 'http://localhost:8000/file/relatorio/excel/baixas/' . Util::getUser() . '/';
                // dd($path);
        Upload::recursive_mkdir($path);
        $filename = "baixas-estoque.csv";
        file_put_contents(public_path($path . $filename), implode("\r\n", $linhas));

        $link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;        

        if($_SERVER['SERVER_NAME'] == 'sandbox.ciesglobal.org')
            $link = '//' . 'homolog.ciesglobal.org' . '/' . $path . $filename;

        return redirect($link);

    }

    public function transferencias()
    {
        $_arenas = Arenas::select('id', 'nome')->whereIn('id', ['1', '2', '13', '3', '4'])->get();
        $arenas = array();

        foreach($_arenas as $arena){
            $arenas[$arena->id] = $arena->nome;
        } 
        return view('admin.estoque.relatorios.transferencias', compact('arenas'));
    }

    public function transferenciasExcel(Request $request)
    {
        $dados = $request->all();

        $transferencias = ProdutosOperacoes::relatorioTransferencias($dados);

        // dd($transferencias);

        if(isset($dados['tela'])){

            // dd($dados);
            
            return view('admin.estoque.relatorios.ajax-transferencias', compact('transferencias'));

        }

        $linhas = array();

        $linhas[] = "Produto;Arena;Lote;Responsavel transferencia;Quantidade;Data Hora;Usuario que recebeu;Data Hora recebimento";        

        foreach($transferencias as $transferencia){
            $linhas[] = implode(';', $transferencia);
        }

        $path = PATH_FILE_RELATORIO . 'excel/transferencias/' . Util::getUser() . '/';
        // $path = 'http://localhost:8000/file/relatorio/excel/baixas/' . Util::getUser() . '/';
                // dd($path);
        Upload::recursive_mkdir($path);
        $filename = "transferencias-estoque.csv";
        file_put_contents(public_path($path . $filename), implode("\r\n", $linhas));

        $link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;        

        if($_SERVER['SERVER_NAME'] == 'sandbox.ciesglobal.org')
            $link = '//' . 'homolog.ciesglobal.org' . '/' . $path . $filename;

        return redirect($link);

    }

    public function produtosVencimentos()
    {
        $_arenas = Arenas::select('id', 'nome')->whereIn('id', ['1', '2', '13', '3', '4'])->get();
        $arenas = array();

        foreach($_arenas as $arena){
            $arenas[$arena->id] = $arena->nome;
        } 
        return view('admin.estoque.relatorios.produtos_vencimento', compact('arenas'));
    }

    public function produtosVencimentosExcel(Request $request)
    {
        $dados = $request->all();

        $produtos = ProdutosLote::relatorioVencimento($dados);

        if(isset($dados['tela'])){

            // dd($produtos);
            
            return view('admin.estoque.relatorios.ajax-vencimentos', compact('produtos'));

        }

        $linhas = array();

        $linhas[] = "Produto;Lote;Arena;Quantidade;Vencimento";        

        foreach($produtos as $produto){
            if(empty($produto['nome_arena'])){
                $produto['nome_arena'] = 'LUTECIA';
            }
            // dd($produto);
            $linhas[] = implode(';', $produto);
        }

        $path = PATH_FILE_RELATORIO . 'excel/vencimentos/' . Util::getUser() . '/';
        // $path = 'http://localhost:8000/file/relatorio/excel/baixas/' . Util::getUser() . '/';
                // dd($path);
        Upload::recursive_mkdir($path);
        $filename = "vencimento-estoque.csv";
        file_put_contents(public_path($path . $filename), implode("\r\n", $linhas));

        $link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;        

        if($_SERVER['SERVER_NAME'] == 'sandbox.ciesglobal.org')
            $link = '//' . 'homolog.ciesglobal.org' . '/' . $path . $filename;

        return redirect($link);

       
    }

    public function produtosEntrada()
    {
        
        return view('admin.estoque.relatorios.entrada');
    }

    public function entradaExcel(Request $request)
    {
        $dados = $request->all();
        //die('aaa');

        $produtos = ProdutosOperacoes::relatorioEntrada($dados);

        if(isset($dados['tela'])){
            
            return view('admin.estoque.relatorios.ajax-entrada', compact('produtos'));

        }

        $linhas = array();

        $linhas[] = "Produto;Lote;Usuario;Quantidade;Data Hora";        

        foreach($produtos as $produto){
           
            $linhas[] = implode(';', $produto);
        }

        

        $path = PATH_FILE_RELATORIO . 'excel/entradas/' . Util::getUser() . '/';
        // $path = 'http://localhost:8000/file/relatorio/excel/baixas/' . Util::getUser() . '/';
                // dd($path);
        Upload::recursive_mkdir($path);
        $filename = "vencimento-estoque.csv";
        file_put_contents(public_path($path . $filename), implode("\r\n", $linhas));

        $link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;        

        if($_SERVER['SERVER_NAME'] == 'sandbox.ciesglobal.org')
            $link = '//' . 'homolog.ciesglobal.org' . '/' . $path . $filename;

        return redirect($link);
    }

    public function produtos()
    {
        
        $lines = array();

        $lines[] = "Produto/Lote;Local;Quantidade";

        $lotes = ProdutosLote::select('produtos_lote.id', 'produtos.nome AS produto', 'produtos_lote.codigo')
                                    ->join('produtos', 'produtos_lote.produto', '=', 'produtos.id')
                                    ->get();

        
        foreach ($lotes as $lote) {
            
            $lines[] = $lote->produto;
            $lines[] = $lote->codigo;

            $quantidades_central = ProdutosQuantidade::select('quantidade')
                                                    ->where('produtos_quantidade.lote', $lote->id)
                                                    ->where('central', 1)
                                                    ->where('quantidade', '>', 0)
                                                    ->first();

            $lines[] = ";LUTECIA;" . $quantidades_central->quantidade;


            $quantidades = ProdutosQuantidade::select('produtos_quantidade.quantidade', 'arenas.nome')
                                            ->join('arenas', 'produtos_quantidade.arena', '=', 'arenas.id')
                                            ->where('produtos_quantidade.lote', $lote->id)
                                            ->where('produtos_quantidade.quantidade', '>', 0)
                                            ->orderBy('arenas.nome', 'ASC')
                                            ->get();

            foreach ($quantidades as $key => $quantidade) {
                $lines[] = ";". $quantidade->nome .";" . $quantidade->quantidade;
            }

            $lines[] = '';
            
            $lines[] = ";Em transferência - Destino";

            $tranferencias = ProdutosOperacoes::select('arenas.nome', 'quantidade')
                                            ->leftjoin('arenas', 'produtos_operacoes.arena', '=', 'arenas.id')
                                            ->join('produtos_lote', 'produtos_operacoes.lote', '=', 'produtos_lote.id')
                                            ->where('produtos_operacoes.lote', $lote->id)
                                            ->where('produtos_operacoes.recebido', 0)
                                            ->where('produtos_operacoes.quantidade', '>', 0)
                                            ->where('produtos_operacoes.tipo_operacao', 'transferencia')
                                            ->get();

            foreach ($tranferencias as $key => $tranferencia) {
                $lines[] = ";". (!empty($tranferencia->nome) ? $tranferencia->nome : 'LUTECIA') .";" . $tranferencia->quantidade;
            }
        }

        $path = PATH_FILE_RELATORIO . 'excel/produtos/' . Util::getUser() . '/';
        // $path = 'http://localhost:8000/file/relatorio/excel/baixas/' . Util::getUser() . '/';
                // dd($path);
        Upload::recursive_mkdir($path);
        $filename = "produtos-estoque.csv";
        file_put_contents(public_path($path . $filename), implode("\r\n", $lines));

        $link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;   
        
        
        if($_SERVER['SERVER_NAME'] == 'sandbox.ciesglobal.org')
            $link = '//' . 'homolog.ciesglobal.org' . '/' . $path . $filename;

        if($_SERVER['SERVER_NAME'] == 'localhost')
            $link = '//' . 'localhost:8000' . '/' . $path . $filename;

        return redirect($link);

    }

    public function fornecedoresExcel()
    {

        $lines = array();

        $lines[] = "FORNECEDOR;ARENA;PRODUTO;QUANTIDADE";
        
        $fornecedores = ProdutosFornecedores::select('id', 'nome_fantasia AS fornecedor')->orderBy('nome_fantasia')->get();

        // dd($fornecedores);

        foreach($fornecedores as $fornecedor){

           // $lines[] = $fornecedor->fornecedor;

            // dd($fornecedor);
            $quantidades_cd = ProdutosQuantidade::select('produtos.nome AS produto', 'produtos_quantidade.quantidade')
                                            ->join('produtos_lote', 'produtos_quantidade.lote', '=', 'produtos_lote.id')
                                            ->join('produtos', 'produtos_quantidade.produto', '=', 'produtos.id')
                                            ->where('produtos_lote.fornecedor', $fornecedor->id)
                                            ->where('produtos_quantidade.central', 1)
                                            ->where('produtos_quantidade.quantidade', '>', 0)
                                            ->get()
                                            ->toArray();

            

           // $lines[] = "LUTECIA";

            foreach($quantidades_cd as $quantidade_cd){
                // dd($quantidade_cd);
                $lines[] = $fornecedor->fornecedor . ";LUTECIA;" . implode(';', $quantidade_cd);
            }  
            
            $quantidades = ProdutosQuantidade::select('arenas.nome AS arena', 'produtos.nome AS produto', 'produtos_quantidade.quantidade')
                                            ->join('arenas', 'produtos_quantidade.arena', '=', 'arenas.id')
                                            ->join('produtos_lote', 'produtos_quantidade.lote', '=', 'produtos_lote.id')
                                            ->join('produtos', 'produtos_quantidade.produto', '=', 'produtos.id')
                                            ->where('produtos_lote.fornecedor', $fornecedor->id)
                                            ->where('produtos_quantidade.central', 0)
                                            ->where('produtos_quantidade.quantidade', '>', 0)
                                            ->groupBy('arenas.nome', 'produtos.nome', 'produtos_quantidade.quantidade')
                                            ->get()
                                            ->toArray();

            foreach($quantidades as $quantidade){
                // dd($quantidade);
                $lines[] = $fornecedor->fornecedor . ";" . implode(';', $quantidade);
            }
            
                                                
        }

        $path = PATH_FILE_RELATORIO . 'excel/produtos/' . Util::getUser() . '/';
        // $path = 'http://localhost:8000/file/relatorio/excel/baixas/' . Util::getUser() . '/';
                // dd($path);
        Upload::recursive_mkdir($path);
        $filename = "produtos-fornecedores.csv";
        file_put_contents(public_path($path . $filename), implode("\r\n", $lines));

        $link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;   
        
        
        if($_SERVER['SERVER_NAME'] == 'sandbox.ciesglobal.org')
            $link = '//' . 'homolog.ciesglobal.org' . '/' . $path . $filename;

        // if($_SERVER['SERVER_NAME'] == 'localhost')
        //     $link = '//' . 'localhost:8000' . '/' . $path . $filename;

        return redirect($link);
    }
}
