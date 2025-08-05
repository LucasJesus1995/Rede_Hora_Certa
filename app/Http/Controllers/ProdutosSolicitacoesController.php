<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Produtos;
use App\Arenas;
use App\ProdutosQuantidade;
use App\ProdutosSolicitacoes;
use App\ProdutosOperacoes;
use App\User;
use Auth;
use Illuminate\Support\Facades\DB;

class ProdutosSolicitacoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $solicitacoes = ProdutosSolicitacoes::lista();

        return view('admin.estoque.solicitacoes.index', compact('solicitacoes'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($arena_id = null)
    {
        $produtos = Produtos::Combo();

        $_arenas = Arenas::select('id', 'nome')->whereIn('id', ['1', '2', '13', '3', '4'])->get();
        $arenas = array();

        foreach($_arenas as $arena){
            $arenas[$arena->id] = $arena->nome;
        }

        if(!empty($arena_id)){
            $solicitacoes = ProdutosSolicitacoes::lista('aberto', $arena_id);
        } else {
            $solicitacoes = null;
        }


        return view('admin.estoque.solicitacoes.create', compact('produtos', 'arenas', 'solicitacoes', 'arena_id'));
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

        $dados['solicitante'] = Auth::user()->id;

        ProdutosSolicitacoes::create($dados);

        return redirect(route('estoque.solicitacoes.create', $dados['arena']));

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
        $produtos = Produtos::Combo();

        $_arenas = Arenas::select('id', 'nome')->whereIn('id', ['1', '2', '13', '3', '4'])->get();
        $arenas = array();

        foreach($_arenas as $arena){
            $arenas[$arena->id] = $arena->nome;
        }

        $solicitacao = ProdutosSolicitacoes::find($id);

        return view('admin.estoque.solicitacoes.edit', compact('solicitacao', 'produtos', 'arenas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $dados = $request->all();

        $solicitacao = ProdutosSolicitacoes::find($dados['id']);

        $solicitacao->update($dados);

        return redirect(route('estoque.solicitacoes.index'));

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

    public function verificar($id)
    {
        $solicitacao = ProdutosSolicitacoes::find($id);
        $solicitante = User::find($solicitacao->solicitante);

        $_produtos = ProdutosQuantidade::select('produtos_quantidade.id', 'produtos_quantidade.quantidade', 'produtos_lote.codigo', 'produtos_lote.vencimento')
                                        ->join('produtos', 'produtos_quantidade.produto', '=', 'produtos.id')
                                        ->join('produtos_lote', 'produtos_quantidade.lote', '=', 'produtos_lote.id')
                                        ->where('produtos_quantidade.produto', $solicitacao->produto)
                                        ->where('produtos_quantidade.quantidade', '>=', $solicitacao->quantidade)
                                        ->where('produtos_lote.vencimento', '>', date('Y-m-d'))
                                        ->where('produtos_quantidade.central', 1)
                                        ->orderBy('produtos_lote.vencimento', 'ASC')
                                        ->get();

        $produtos = array();

        foreach ($_produtos as $key => $produto) {
            $produtos[$produto->id] = $produto->codigo . '-' . \App\Http\Helpers\Util::DB2User($produto->vencimento) . '(' . $produto->quantidade . ')';
        }

        $produto = Produtos::find($solicitacao->produto);

// die;
        return view('admin.estoque.solicitacoes.verificar', compact('solicitacao', 'produto', 'produtos', 'solicitante', '_produtos'));
    }

    public function confirmar(Request $request)
    {
        $dados = $request->all();

        $solicitacao = ProdutosSolicitacoes::find($dados['id']);

        $quantidade = ProdutosQuantidade::find($dados['produto_quantidade']);

        if($quantidade){

            DB::beginTransaction();

            try {
                $nova_quantidade = $quantidade->quantidade - $solicitacao->quantidade;

                $quantidade->update(['quantidade' => $nova_quantidade]);

                ProdutosOperacoes::create([
                    'produto' => $solicitacao->produto,
                    'solicitacao' => $dados['id'],
                    'lote' => $quantidade->lote,
                    'quantidade' => $solicitacao->quantidade,
                    'arena' => $solicitacao->arena,
                    'user' => Auth::user()->id,
                    'tipo_operacao' => 'transferencia'
                ]);

                $solicitacao->update([
                    'status' => 'aprovado',
                    'responsavel' => Auth::user()->id,
                ]);

                DB::commit();
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
            }

            


        }

        return redirect(route('estoque.solicitacoes.index'));    
    }
}
