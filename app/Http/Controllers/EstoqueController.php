<?php

namespace App\Http\Controllers;

use App\Arenas;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Produtos;
use App\ProdutosLote;
use App\ProdutosOperacoes;
use App\ProdutosQuantidade;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar($msg = null)
    {

        return view('admin.estoque.adicionar', compact('msg'));
        
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados = $request->all();

        
        $dados['vencimento'] = \App\Http\Helpers\Util::Date2DB($dados['vencimento']);

        $lote = ProdutosLote::create($dados);

        $dados['lote'] = $lote->id;

        $quantidade = ProdutosQuantidade::select('id', 'quantidade')
                                        ->where('central', 1)
                                        ->where('lote', $dados['lote'])
                                        ->first();

        
        DB::beginTransaction();
        
        try{
            if($quantidade){

                $nova_quantidade = $quantidade->quantidade + $dados['quantidade'];
    
                $quantidade->update(['quantidade' => $nova_quantidade]);
    
    
            } else {
                $dados['central'] = 1;
                ProdutosQuantidade::create($dados);
            }
    
            $dados['user'] = Auth::user()->id;
            $dados['tipo_operacao'] = 'adicao';
    
            ProdutosOperacoes::create($dados);
    
            DB::commit();

        } catch (\Exception $e){
            DB::rollback();
        } 
        
        return redirect(route('estoque.adicionar', ['Adicionado com sucesso!']));

    }

    public function transferir($arena_id = null, $mensagem = null)
    {
        $_arenas = Arenas::select('id', 'nome')->whereIn('id', ['1', '2', '13', '3', '4'])->get();
        $arenas = array();
        $origem = array();
        $origem[0] = 'LUTÉCIA';

        foreach($_arenas as $arena){
            $arenas[$arena->id] = $arena->nome;
            $origem[$arena->id] = $arena->nome;
        }

        // dd($arenas);

        return view('admin.estoque.transferir', compact('arena_id', 'arenas', 'origem', 'mensagem'));
    }

    public function carregarLotesQuantidade($produto, $origem)
    {

        if($origem == 0){
            $whr = 'produtos_quantidade.central = 1';
        } else {
            $whr = 'produtos_quantidade.arena = ' . $origem;
        }

        $quantidades = ProdutosQuantidade::select('produtos_quantidade.lote', 'produtos_lote.codigo', 'produtos_lote.vencimento', 
                                            'produtos_quantidade.quantidade')
                                            ->join('produtos_lote', 'produtos_quantidade.lote', '=', 'produtos_lote.id')
                                            // ->where('produtos_quantidade.central', 1)
                                            ->whereRaw($whr)
                                            ->where('produtos_quantidade.quantidade', '>', 0)
                                            ->where('produtos_quantidade.produto', $produto)
                                            ->get();

        $array_quantidades = array();

        foreach($quantidades as $key => $quantidade){
            $array_quantidades[$key]['id'] = $quantidade->lote;
            $array_quantidades[$key]['nome'] = "{$quantidade->codigo} - " . date('d/m/Y', strtotime($quantidade->vencimento));
            $array_quantidades[$key]['quantidade'] = $quantidade->quantidade;
        }

        return $array_quantidades;
    }

    public function transferencias()
    {
        $transferencias = ProdutosOperacoes::select('produtos_operacoes.id', 'produtos_operacoes.uuid', 'produtos.nome AS produto', 
                                                    'produtos_operacoes.quantidade', 'arenas.nome AS arena', 'arena_origem.nome AS nome_origem', 
                                                    'produtos_operacoes.created_at')
                                            ->join('produtos', 'produtos_operacoes.produto', '=', 'produtos.id')
                                            ->leftjoin('arenas', 'produtos_operacoes.arena', '=', 'arenas.id')                                            
                                            ->leftjoin('arenas AS arena_origem', 'produtos_operacoes.origem', '=', 'arena_origem.id')                                            
                                            ->where('tipo_operacao', 'transferencia')
                                            ->where('recebido', 0)
                                            ->paginate();

        return view('admin.estoque.transferencias', compact('transferencias'));
    }

    public function transferirStore(Request $request)
    {
        $dados = $request->all();

        if($dados['origem'] == 0){
            $whr = 'central = 1';
            $dados['origem'] = null;
        } else {
            $whr = 'arena = ' . $dados['origem'];
        }

        $quantidade = ProdutosQuantidade::select('id', 'quantidade')
                                        // ->where('central', 1)
                                        ->whereRaw($whr)
                                        ->where('lote', $dados['lote'])
                                        ->first();

        $arena = $dados['arena'];

        if($dados['arena'] == 0){
            $dados['arena'] = null;
        }
        
        $nova_quantidade = $quantidade->quantidade - $dados['quantidade'];



        if($nova_quantidade < 0) {
            return redirect(route('estoque.transferir', [$dados['arena'], 'Estoque insuficiente!']));

        } else {
            DB::beginTransaction();

            try{
                $quantidade->update(['quantidade' => $nova_quantidade]);

                $dados['user'] = Auth::user()->id;
                $dados['tipo_operacao'] = 'transferencia';
                $dados['uuid'] = uniqid();
                
                ProdutosOperacoes::create($dados);

                DB::commit();
            } catch (\Exception $e){
                DB::rollback();
            }

            return redirect(route('estoque.transferir', $arena));


        }        



        DB::beginTransaction();

        try{
            $quantidade->update(['quantidade' => $nova_quantidade]);

            $dados['user'] = Auth::user()->id;
            $dados['tipo_operacao'] = 'transferencia';
            $dados['uuid'] = uniqid();

            ProdutosOperacoes::create($dados);

            DB::commit();
        } catch (\Exception $e){
            DB::rollback();
        }


    }

    public function receber($status = null)
    {
        $_arenas = Arenas::select('id', 'nome')->whereIn('id', ['1', '2', '13', '3', '4'])->get();
        $arenas = array();

        foreach($_arenas as $arena){
            $arenas[0] = 'LUTÉCIA';
            $arenas[$arena->id] = $arena->nome;
        }
        return view('admin.estoque.receber', compact('status', 'arenas'));
    }

    public function receberConfirma(Request $request)
    {
        $dados = $request->all();

        $operacao = ProdutosOperacoes::select('produtos_operacoes.uuid','produtos_operacoes.quantidade',
                                                'arenas.nome AS arena', 'produtos.nome AS produto')
                                    ->join('produtos', 'produtos_operacoes.produto', '=', 'produtos.id')
                                    ->join('arenas', 'produtos_operacoes.arena', '=', 'arenas.id')
                                    ->where('uuid', $dados['uuid'])
                                    ->where('recebido', 0)
                                    ->first();
                
        return view('admin.estoque.receber_confirma', compact('operacao'));
    }

    public function receberStore($id)
    {

        $operacao = ProdutosOperacoes::find($id);

        
                
        if($operacao){
            
            // return ($operacao->arena);

            if($operacao->arena == null){
                // return 'null';
                $whr = 'central = 1';
            } else {
                $whr = 'arena = ' . $operacao->arena;
            }

            $quantidade = ProdutosQuantidade::select('id', 'quantidade')
                                            ->where('lote', $operacao->lote)
                                            ->whereRaw($whr)
                                            ->first();
            
            

            DB::beginTransaction();

            try{            
                if($quantidade){

                    $nova_quantidade = (int) $quantidade->quantidade + (int) $operacao->quantidade;      

                    $quantidade->update(['quantidade' => $nova_quantidade]);

                } else {
                    ProdutosQuantidade::create([
                        'produto'       => $operacao->produto,
                        'lote'          => $operacao->lote,
                        'arena'         => $operacao->arena,
                        'quantidade'    => $operacao->quantidade,
                    ]);
                }           

                $operacao->update([
                    'recebido'      => 1,
                    'data_recebido' => date('Y-m-d H:i:s'),
                    'user_recebido' => Auth::user()->id
                ]);

                DB::commit();
            } catch (\Exception $e){
                DB::rollback();
            }
        }

        return 'ok';
    }

    public function verEstoques($produto)
    {
        $produto = Produtos::find($produto);

        // Central

        $quantidades_central = ProdutosQuantidade::select('quantidade', 'produtos_lote.codigo', 'produtos_lote.vencimento')
                                                    ->join('produtos_lote', 'produtos_quantidade.lote', '=', 'produtos_lote.id')
                                                    ->where('produtos_quantidade.produto', $produto->id)
                                                    ->where('central', 1)
                                                    ->get();

        $quantidades = ProdutosQuantidade::select('produtos_quantidade.quantidade', 'arenas.nome', 'produtos_lote.codigo', 'produtos_lote.vencimento')
                                            ->join('arenas', 'produtos_quantidade.arena', '=', 'arenas.id')
                                            ->join('produtos_lote', 'produtos_quantidade.lote', '=', 'produtos_lote.id')
                                            ->where('produtos_quantidade.produto', $produto->id)
                                            ->orderBy('arenas.nome', 'ASC')
                                            ->get();

        $tranferencias = ProdutosOperacoes::select('arenas.nome', 'quantidade', 'produtos_lote.codigo', 'produtos_lote.vencimento')
                                            ->join('arenas', 'produtos_operacoes.arena', '=', 'arenas.id')
                                            ->join('produtos_lote', 'produtos_operacoes.lote', '=', 'produtos_lote.id')
                                            ->where('produtos_operacoes.produto', $produto->id)
                                            ->where('produtos_operacoes.recebido', 0)
                                            ->where('produtos_operacoes.quantidade', '>', 0)
                                            ->where('produtos_operacoes.tipo_operacao', 'transferencia')
                                            ->get();

        
        return view('admin.estoque.ver_estoques', compact('produto', 'quantidades_central', 'quantidades', 'tranferencias'));


    }

    public function arenas()
    {
        $_arenas = ProdutosQuantidade::select('arenas.id', 'arenas.nome')
                            ->join('arenas', 'produtos_quantidade.arena', '=', 'arenas.id')
                            ->where('central', 0)
                            ->groupBy('arenas.id', 'arenas.nome')
                            ->get();
        
        $arenas = array();

        foreach($_arenas as $arena){
            $arenas[$arena->id] = $arena->nome;
        }
        
        // dd($arenas);
        return view('admin.estoque.arenas', compact('arenas'));
    }

    public function arenasEstoque($arena, $excel = false)
    {
        $quantidades = ProdutosQuantidade::select('produtos.nome', 'produtos_quantidade.quantidade')
                                            ->join('produtos', 'produtos_quantidade.produto', '=', 'produtos.id')
                                            ->where('produtos_quantidade.arena', $arena)
                                            ->get();

        if($excel){

            $_arena = Arenas::find($arena);

            $lines = array();        

            $lines[] = "{$_arena->nome}";
            $lines[] = "Produto;Quantidade";

            foreach($quantidades as $quantidade){
                $lines[] = $quantidade->nome . ';' . $quantidade->quantidade;
            }

            $path = PATH_FILE_RELATORIO . 'excel/estoque/' . Util::getUser() . '/';
            // dd($path);
            Upload::recursive_mkdir($path);
            $filename = "arenas-estoque.csv";
            file_put_contents(public_path($path . $filename), implode("\r\n", $lines));

            $link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;

            return redirect($link);

        }

        return view('admin.estoque.arenas_estoque', compact('quantidades'));
    }
    
    
    public function baixar($status = null)
    {
        $_arenas = ProdutosQuantidade::select('arenas.id', 'arenas.nome')
                            ->join('arenas', 'produtos_quantidade.arena', '=', 'arenas.id')
                            ->where('central', 0)
                            ->where('quantidade', '>', 0)
                            ->whereRaw('lote IS NOT NULL')
                            ->groupBy('arenas.id', 'arenas.nome')
                            ->get();
        
        $arenas = array();

        foreach($_arenas as $arena){
            $arenas[$arena->id] = $arena->nome;
        }

        
        
        // dd($arenas);
        return view('admin.estoque.baixar', compact('arenas', 'status'));
    }

    public function arenaProdutos($arena)
    {
        
        $produtos = ProdutosQuantidade::select('produtos_quantidade.id', 'produtos.nome', 'produtos_lote.codigo', 'produtos_lote.vencimento', 'quantidade')
                            ->join('produtos', 'produtos_quantidade.produto', '=', 'produtos.id')
                            ->join('produtos_lote', 'produtos_quantidade.lote', '=', 'produtos_lote.id')
                            ->where('arena', $arena)
                            ->where('quantidade', '>', 0)
                            ->get();                             
                            
        $array_produtos = array();

        foreach($produtos as $key => $produto){
            $array_produtos[$key]['id'] = $produto->id;
            $array_produtos[$key]['nome'] = $produto->nome . ' / ' . $produto->codigo . ' / ' . date('d/m/Y', strtotime($produto->vencimento));
            $array_produtos[$key]['quantidade'] = $produto->quantidade;
        }

        return $array_produtos;

    }    

    public function baixarStore(Request $request)
    {
        $dados = $request->all();

        // dd($dados);

        $quantidade = ProdutosQuantidade::find($dados['produto_quantidade']);

        // dd($quantidade);

        $nova_quantidade = $quantidade->quantidade - $dados['quantidade'];

        DB::beginTransaction();

        try{

            ProdutosOperacoes::create([
                'produto'       => $quantidade->produto,
                'lote'          => $quantidade->lote,
                'quantidade'    => $dados['quantidade'],
                'arena'         => $dados['arena'],
                'tipo_operacao' => 'baixa',
                'tipo_baixa'    => $dados['tipo_baixa'],
                'tipo_consumo'  => (empty($dados['tipo_consumo']) ? null: $dados['tipo_consumo']),
                'user'          => Auth::user()->id,
            ]);

            $quantidade->update(['quantidade' => $nova_quantidade]);

            DB::commit();

        } catch (\Exception $e){
            DB::rollback();
        }

        return redirect(route('estoque.baixar', ['Baixado com sucesso!']));

    }

    public function verTransferencias($arena, $data_transeferencia, $pdf = false)
    {
        if($arena == 0){
            $arena = null;
            $arena_nome = 'LUTÉCIA';
        } else {
            $_arena = Arenas::find($arena);
            $arena_nome = $_arena->nome;
        }
        $transferencias = ProdutosOperacoes::select('produtos.nome AS produto', 'produtos_lote.codigo AS lote', 'produtos_operacoes.quantidade',
                                                    'produtos_operacoes.created_at')
                                            ->join('produtos', 'produtos_operacoes.produto', '=', 'produtos.id')
                                            ->join('produtos_lote', 'produtos_operacoes.lote', '=', 'produtos_lote.id')
                                            ->where('produtos_operacoes.arena', $arena)
                                            ->where('produtos_operacoes.created_at', '>=', $data_transeferencia . ' 00:00:00')
                                            ->where('produtos_operacoes.created_at', '<=', $data_transeferencia . ' 23:59:59')
                                            ->where('produtos_operacoes.tipo_operacao', 'transferencia')
                                            ->where('produtos_operacoes.recebido', 0)
                                            ->get();

                                            // dd($transferencias);

        $view = view('admin.estoque.ver_transferencias', compact('transferencias', 'pdf', 'arena_nome'));

        if($pdf){
            $contents = $view->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($contents, 'UTF-8');
            $dompdf->setPaper('A4');
            $dompdf->render();

            $nome_arquivo = "transferencias";
            $dompdf->stream($nome_arquivo, array("Attachment" => false));
            die;
        }

        return $view;       
                                            
    }

    public function verTransferenciasReceber($arena, $data_transeferencia, $pdf = false)
    {
        $_arena = Arenas::find($arena);
        if($arena == 0) {
            $arena = null;
        }
        $transferencias = ProdutosOperacoes::select('produtos_operacoes.id', 'produtos.nome AS produto', 'produtos_lote.codigo AS lote', 
                                                    'produtos_operacoes.quantidade', 'produtos_operacoes.recebido',
                                                    'produtos_operacoes.created_at', 'produtos_operacoes.data_recebido', 'users.name', 'responsavel.name AS resp')
                                            ->join('produtos', 'produtos_operacoes.produto', '=', 'produtos.id')
                                            ->join('produtos_lote', 'produtos_operacoes.lote', '=', 'produtos_lote.id')
                                            ->leftjoin('users', 'produtos_operacoes.user_recebido', '=', 'users.id')
                                            ->join('users AS responsavel', 'produtos_operacoes.user', '=', 'responsavel.id')
                                            ->where('produtos_operacoes.arena', $arena)
                                            ->where('produtos_operacoes.created_at', '>=', $data_transeferencia . ' 00:00:00')
                                            ->where('produtos_operacoes.created_at', '<=', $data_transeferencia . ' 23:59:59')
                                            ->where('produtos_operacoes.tipo_operacao', 'transferencia')  
                                            ->orderBy('recebido', 'DESC')                                          
                                            ->get();

                                            // dd($transferencias);

        $view = view('admin.estoque.ver_transferencias_receber', compact('transferencias', 'pdf', '_arena'));

        if($pdf){
            $contents = $view->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($contents, 'UTF-8');
            $dompdf->setPaper('A4');
            $dompdf->render();

            $nome_arquivo = "transferencias";
            $dompdf->stream($nome_arquivo, array("Attachment" => false));
            die;
        }

        return $view;       
                                            
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
