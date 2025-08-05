<?php

namespace App\Http\Controllers;

use App\Insumos;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InsumosController extends Controller
{
    public $model = 'Insumos';

    use TraitController;

    public function __construct() {
        $this->title = "app.insumos";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql  = $this->objModel->select('id','nome','ativo')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->oRwhere('nome', 'LIKE', "%{$params}%");
        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\UnidadesRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
