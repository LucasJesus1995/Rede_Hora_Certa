<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CboController extends Controller
{
    public $model = 'Cbo';

    use TraitController;

    public function __construct() {
        $this->title = "app.cbo";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('id','nome','codigo', 'ativo')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->Orwhere('nome', 'LIKE', "%{$params}%")
                ->Orwhere('codigo', 'LIKE', "%{$params}%")
                ->Orwhere('id', '=', $params);

        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\CboRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
