<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProdutosFabricantesRequest;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ProdutosFabricantes;

class ProdutosFabricantesController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fabricantes = ProdutosFabricantes::lista();

        return view('admin.estoque.fabricantes.index', compact('fabricantes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.estoque.fabricantes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdutosFabricantesRequest $request)
    {
        $dados = $request->all();
        
        $dados['cnpj'] = \App\Http\Helpers\Util::somenteNumeros($dados['cnpj']);
        $dados['cep'] = \App\Http\Helpers\Util::somenteNumeros($dados['cep']);

        $verifica = ProdutosFabricantes::select('id')->where('cnpj', $dados['cnpj'])->first();

        if($verifica){
            die('ja tem');
        }

        try {
            ProdutosFabricantes::create($dados);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }

        return redirect(route('estoque.fabricantes.index'));


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
        $fornecedor = ProdutosFabricantes::find($id);

        return view('admin.estoque.fabricantes.edit', compact('fornecedor'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProdutosFabricantesRequest $request)
    {
        $dados = $request->all();
        $fornecedor = ProdutosFabricantes::find($dados['id']);

        $dados['cnpj'] = \App\Http\Helpers\Util::somenteNumeros($dados['cnpj']);
        $dados['cep'] = \App\Http\Helpers\Util::somenteNumeros($dados['cep']);

        $fornecedor->update($dados);

        return redirect(route('estoque.fabricantes.index'));

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
