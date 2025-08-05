<?php

namespace App\Http\Controllers;

use App\Unidades;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UnidadesController extends Controller
{
    public $model = 'Unidades';

    use TraitController;

    public function __construct() {
        $this->title = "app.unidades";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select(['unidades.id','unidades.nome','unidades.ativo','pais.nome AS pais'])
            ->join('pais', 'pais.id', '=', 'unidades.pais')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->oRwhere('unidades.nome', 'LIKE', "%{$params}%")
                ->oRwhere('pais.nome', 'LIKE', "%{$params}%");
        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\UnidadesRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
