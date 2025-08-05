<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ProdutosFornecedoresRequest;

use App\Http\Controllers\Controller;

use App\ProdutosFornecedores;

class ProdutosFornecedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fornecedores = ProdutosFornecedores::lista();

        return view('admin.estoque.fornecedores.index', compact('fornecedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.estoque.fornecedores.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdutosFornecedoresRequest $request)
    {
        $dados = $request->all();
        
        $dados['cnpj'] = \App\Http\Helpers\Util::somenteNumeros($dados['cnpj']);
        $dados['cep'] = \App\Http\Helpers\Util::somenteNumeros($dados['cep']);

        $verifica = ProdutosFornecedores::select('id')->where('cnpj', $dados['cnpj'])->first();

        if($verifica){
            die('ja tem');
        }

        try {
            ProdutosFornecedores::create($dados);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }

        return redirect(route('estoque.fornecedores.index'));


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
        $fornecedor = ProdutosFornecedores::find($id);

        return view('admin.estoque.fornecedores.edit', compact('fornecedor'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProdutosFornecedoresRequest $request)
    {
        $dados = $request->all();
        $fornecedor = ProdutosFornecedores::find($dados['id']);

        $dados['cnpj'] = \App\Http\Helpers\Util::somenteNumeros($dados['cnpj']);
        $dados['cep'] = \App\Http\Helpers\Util::somenteNumeros($dados['cep']);

        $fornecedor->update($dados);

        return redirect(route('estoque.fornecedores.index'));

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
