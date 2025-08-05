<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Cid;
use App\ProcedimentoCids;
use Illuminate\Support\Facades\Input;

class ProcedimentosController extends Controller
{
    public $model = 'Procedimentos';

    use TraitController;

    public function __construct() {
        $this->title = "app.procedimentos";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql  = $this->objModel->select('id','nome','ativo','operacional','forma_faturamento','autorizacao','sus', 'ordem')
            ->orderBy('ativo','desc')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->oRwhere('nome', 'LIKE', "%{$params}%")
                ->oRwhere('id', '=', $params);

        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function getEntry($id = null) {
        $view = View("admin.{$this->layout}.entry")->with('title', $this->title);

        $entry = null;
        if ($id) {
            $entry = $this->objModel->find($id);

          $entry['linha_cuidado'] = array_values(\App\LinhaCuidadoProcedimentos::where('procedimento', $id)->get()->lists('linha_cuidado')->toArray());
        }

        $view->entry = $entry;

        return $view;
    }

    public function postIndex(Requests\ProcedimentosRequest $request) {
        $save = $this->objModel->saveData($request->all());

        return redirect("admin/{$this->layout}/list");
    }

    public function getCids($procedimento) {
        $view = View("admin.{$this->layout}.cids");

        $view->cids = Cid::getAll();
        $view->procedimento_cids = ProcedimentoCids::getByProcedimento($procedimento);
        $view->procedimento = $procedimento;
        return $view;
    }

    public function postCids(){
        $dados = Input::all();

        $procedimento_cids = ProcedimentoCids::saveData($dados);

        return [];
    }

}
