<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\TraitController;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AgendamentoTipoController extends Controller
{
    public $model = 'AgendamentoTipo';

    use TraitController;

    public function __construct() {
        $this->title = "Agendamento Tipo";

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

    public function postIndex(Requests\AgendamentoTipoRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
