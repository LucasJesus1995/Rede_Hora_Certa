<?php

namespace App\Http\Controllers;

use App\Empresas;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmpresasController extends Controller
{
    public $model = 'Empresas';

    use TraitController;

    public function __construct() {
        $this->title = "app.empresas";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('id','nome','ativo')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->oRwhere('nome', 'LIKE', "%{$params}%");
        }

        $view->grid = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\ArenasRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
