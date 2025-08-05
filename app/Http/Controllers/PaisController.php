<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PaisController extends Controller
{
    public $model = 'Pais';

    use TraitController;

    public function __construct() {
        $this->title = "app.pais";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select(['pais.id','pais.nome','pais.ativo', 'empresas.nome AS empresa' ])
            ->join('empresas', 'empresas.id', '=', 'pais.empresa')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->oRwhere('empresas.nome', 'LIKE', "%{$params}%");
            $sql->oRwhere('pais.nome', 'LIKE', "%{$params}%");
        }

        $view->grid = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\PaisRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
