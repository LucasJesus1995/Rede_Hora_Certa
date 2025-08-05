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

class CondutasController extends Controller
{
    public $model = 'Condutas';

    use TraitController;

    public function __construct() {
        $this->title = "Condutas";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('condutas.id','condutas.nome','condutas.ativo','condutas.regulacao', 'tipo_atendimento.nome AS tipo_atendimento', 'linha_cuidado.nome AS especialidade', 'condutas.ativo', 'condutas.valida_regulacao')
            ->join('tipo_atendimento', 'tipo_atendimento.id', '=', 'condutas.tipo_atendimento')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'condutas.especialidade')
            ->orderBy('condutas.id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->Orwhere('condutas.nome', 'LIKE', "%{$params}%")
                ->Orwhere('tipo_atendimento.nome', 'LIKE', "%{$params}%")
                ->Orwhere('linha_cuidado.nome', 'LIKE', "%{$params}%")
                ->Orwhere('condutas.id', '=', $params);

        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\CondutasRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
