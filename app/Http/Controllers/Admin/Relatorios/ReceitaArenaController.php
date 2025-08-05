<?php
namespace App\Http\Controllers\Admin\Relatorios;


use App\Arenas;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Relatorios\ReceitaArenaHelpers;
use App\Http\Requests\Admin\Relatorios\ReceitaArenaRequest;
use App\LinhaCuidado;
use Illuminate\Http\Request;

class ReceitaArenaController  extends Controller{

    public function getIndex(){
        $view = View('admin.relatorios.receita-arena.index');

        return $view;
    }

    public function postIndex(ReceitaArenaRequest $request){
        $view = View('admin.relatorios.receita-arena.data');
        $view->contrato = $request->get('contrato');
        $view->faturamento = $request->get('faturamento');

        $sql = ReceitaArenaHelpers::getReceitaArenas($request->get('contrato'), $request->get('faturamento'));
        $view->res = $sql->get();

        return $view;
    }

    public function postDetalhesLinhaCuidado(Request $request){
        $view = View('admin.relatorios.receita-arena.data-detalhes-linha-cuidado');

        $view->arena = $arena = Arenas::find($request->get('arena'));
        $view->contrato = $contrato = $request->get('contrato');
        $view->faturamento = $faturamento = $request->get('faturamento');

        $sql = ReceitaArenaHelpers::getReceitaArenas($request->get('contrato'), $request->get('faturamento'), $arena->id);
        $view->res = $sql->get();

        return $view;
    }

    public function postDetalhesLinhaCuidadoProcedimentos(Request $request){
        $view = View('admin.relatorios.receita-arena.data-detalhes-linha-cuidado-procedimentos');

        $view->arena = $arena = Arenas::find($request->get('arena'));
        $view->contrato = $contrato = $request->get('contrato');
        $view->faturamento = $faturamento = $request->get('faturamento');
        $view->linha_cuidado = $linha_cuidado = LinhaCuidado::find($request->get('linha_cuidado'));

        $sql = ReceitaArenaHelpers::getReceitaArenas($contrato, $faturamento, $arena->id, $linha_cuidado->id);
        $view->res = $sql->get();

        return $view;
    }

}