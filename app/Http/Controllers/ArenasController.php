<?php

namespace App\Http\Controllers;

use App\Arenas;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ArenasController extends Controller
{
    public $model = 'Arenas';

    use TraitController;

    public function __construct() {
        $this->title = "app.arenas";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql  = $this->objModel->select('id','nome', 'alias', 'ativo','cnes')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->oRwhere('nome', 'LIKE', "%{$params}%");
            $sql->oRwhere('cnes', 'LIKE', "%{$params}%");
        }

        $view->grid = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function getEntry($id = null) {
        $view = View("admin.{$this->layout}.entry")->with('title', $this->title);

        $entry = null;
        if ($id) {
            $entry = $this->objModel->find($id);

            $entry['unidade'] = array_values(\App\UnidadesArenas::where('arena', $id)->get()->lists('unidade')->toArray());
            $entry['linha_cuidado'] = array_values(\App\ArenasLinhaCuidado::where('arena', $id)->get()->lists('linha_cuidado')->toArray());
        }

        $view->entry = $entry;

        return $view;
    }

    public function postIndex(Requests\ArenasRequest $request) {
        $save = $this->objModel->saveData($request->all());

        return redirect("admin/{$this->layout}/list");
    }
}
