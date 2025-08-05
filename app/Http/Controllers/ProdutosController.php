<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutosRequest;
use App\Produtos;
use App\ProdutosCategorias;
use Illuminate\Support\Facades\Auth;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produtos = Produtos::select('produtos.id', 'produtos.nome', 'produtos_categorias.nome AS categoria', 'produtos.descricao',
                                    'produtos.unidade_medida', 'produtos_tipos_apresentacoes.nome AS tipo_apresentacao')
                            ->leftjoin('produtos_categorias', 'produtos.categoria', '=', 'produtos_categorias.id')
                            ->leftjoin('produtos_tipos_apresentacoes', 'produtos.tipo_apresentacao', '=', 'produtos_tipos_apresentacoes.id')
                            ->orderBy('produtos.nome', 'ASC')
                            ->paginate(10);

        return view('admin.estoque.produtos.index', compact('produtos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.estoque.produtos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdutosRequest $request)
    {
        $dados = $request->all();

        $dados['nome'] = mb_strtoupper($dados['nome']);
        $dados['codigo'] = mb_strtoupper($dados['codigo']);
        $dados['user'] = Auth::user()->id;

        Produtos::create($dados);

        return redirect(route('produtos.index'));
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
        $pagina_anterior = $_SERVER['HTTP_REFERER'];
        $produto = Produtos::find($id);
        return view('admin.estoque.produtos.edit', compact('produto', 'pagina_anterior'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProdutosRequest $request)
    {
        $dados = $request->all();
        $dados['nome'] = mb_strtoupper($dados['nome']);
        $dados['codigo'] = mb_strtoupper($dados['codigo']);
        $produto = Produtos::find($dados['id']);
        $produto->update($dados);
        return redirect($dados['pagina_anterior']);
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
