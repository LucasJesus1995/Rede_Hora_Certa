<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\TraitController;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CidController extends Controller
{
    public $model = 'Cid';

    use TraitController;

    public function __construct() {
        $this->title = "app.cid";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('id','descricao','codigo')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->Orwhere('descricao', 'LIKE', "%{$params}%")
                ->Orwhere('codigo', 'LIKE', "%{$params}%")
                ->Orwhere('id', '=', $params);

        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\CidRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
