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

class ProgramasController extends Controller
{
    public $model = 'Programas';

    use TraitController;

    public function __construct() {
        $this->title = "Programas";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('id','nome','alias','ativo')
            ->orderBy('id','asc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->Orwhere('alias', 'LIKE', "%{$params}%")
                ->Orwhere('nome', 'LIKE', "%{$params}%")
                ->Orwhere('id', '=', $params);

        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function getLinhasCuidado($programa) {
        $view = View("admin.{$this->layout}.linha-cuidado");

        
        $view->linha_cuidado = LinhaCuidado::where('ativo', true)->get();
        $view->linha_cuidado_programa = LinhaCuidadoProgramas::getByPrograma($programa);
        $view->programa = $programa;
        return $view;
    }

    public function getArenas($programa) {
        $view = View("admin.{$this->layout}.arenas");


        $view->arenas = Arenas::where('ativo', 1)->get();
        $view->arenas_programa = ArenasProgramas::getByPrograma($programa);
        $view->programa = $programa;
        return $view;
    }
    
    public function postLinhasCuidado() {
        $dados = Input::all();

        $linha_cuidado_programa = LinhaCuidadoProgramas::saveData($dados);

        return [];
    }

    public function postArenas() {
        $dados = Input::all();

        $arenas_programa = ArenasProgramas::saveData($dados);

        return [];
    }

    public function postIndex(Requests\ProgramasRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
