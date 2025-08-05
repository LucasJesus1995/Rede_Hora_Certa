<?php

namespace App\Http\Controllers\Admin;

use App\Arenas;
use App\ArenasProgramas;
use App\Http\Controllers\TraitController;
use Illuminate\Http\Request;
use App\LinhaCuidado;
use App\LinhaCuidadoProgramas;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class TiposAtendimentoController extends Controller
{
    public $model = 'TipoAtendimento';

    use TraitController;

    public function __construct() {
        $this->title = "Tipo Atendimento";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('id','nome','ativo')
            ->orderBy('id','asc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->Orwhere('nome', 'LIKE', "%{$params}%")
                ->Orwhere('id', '=', $params);

        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\TipoAtendimentoRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
