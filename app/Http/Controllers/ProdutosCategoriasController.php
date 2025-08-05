<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutosCategoriasRequest;
use App\ProdutosCategorias;
use Illuminate\Support\Facades\Auth;

class ProdutosCategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produtos_categorias = ProdutosCategorias::select('id', 'nome')->orderBy('nome', 'ASC')->paginate(10);

        return view('admin.estoque.produtos_categorias.index', compact('produtos_categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.estoque.produtos_categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdutosCategoriasRequest $request)
    {
        // dd('sss');
        $dados = $request->all();

        $dados['nome'] = mb_strtoupper($dados['nome']);

        ProdutosCategorias::create($dados);

        return redirect(route('produtos_categorias.index'));
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
        $produto_categoria = ProdutosCategorias::find($id);
        return view('admin.estoque.produtos_categorias.edit', compact('produto_categoria', 'pagina_anterior'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProdutosCategoriasRequest $request)
    {
        $dados = $request->all();
        $dados['nome'] = mb_strtoupper($dados['nome']);
        $produtos_categoria = ProdutosCategorias::find($dados['id']);
        $produtos_categoria->update($dados);
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
