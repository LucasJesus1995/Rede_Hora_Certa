<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\TraitController;
use Illuminate\Http\Request;
use App\Cid;
use App\GuiasCids;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class GuiasController extends Controller
{
    public $model = 'Guias';

    use TraitController;

    public function __construct() {
        $this->title = "Guias";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('id','nome','descricao')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->Orwhere('nome', 'LIKE', "%{$params}%")
                ->Orwhere('descricao', 'LIKE', "%{$params}%")
                ->Orwhere('id', '=', $params);

        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\GuiasRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }

    public function getCids($guia) {
        $view = View("admin.{$this->layout}.cids");

        $view->cids = Cid::getAll();
        $view->guia_cids = GuiasCids::getByGuias($guia);
        $view->guia = $guia;
        return $view;
    }

    public function postCids(){
        $dados = Input::all();

        $guia_cids = GuiasCids::saveData($dados);

        return [];
    }
}
