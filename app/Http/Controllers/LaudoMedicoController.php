<?php

namespace App\Http\Controllers;

use App\AtendimentoLaudo;
use App\LaudoMedico;
use Illuminate\Http\Request;

use App\Http\Helpers\Util;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LaudoMedicoController extends Controller
{
    public $model = 'LaudoMedico';

    use TraitController;

    public function getLaudo($id){
        $laudo = current(AtendimentoLaudo::where(array('id'=>$id))->get()->toArray());
        $laudo['descricao'] = urldecode($laudo['descricao']);

        exit(json_encode($laudo));
    }


    public function __construct() {
        $this->title = "app.laudo-medico";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('laudo_medico.id','laudo_medico.nome','linha_cuidado.nome AS linha_cuidado','laudo_medico.ativo', 'profissionais.nome AS medico')
            ->leftJoin('profissionais','profissionais.id','=','laudo_medico.medico')
            ->leftJoin('linha_cuidado','linha_cuidado.id','=','laudo_medico.linha_cuidado')
            ->orderBy('laudo_medico.id','desc');

        $medico = strtoupper(\Illuminate\Support\Facades\Input::get('medico', null));
        if($medico) {
            $sql->where('profissionais.id', '=',$medico);
        }

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->whereRaw("(`laudo_medico`.`nome` LIKE '%{$params}%')");
        }

//        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
//        if($params) {
//            $sql->oRwhere('laudo_medico.nome', 'LIKE', "%{$params}%");
//            $sql->oRwhere('laudo_medico.abreviacao', 'LIKE', "%{$params}%");
//        }



        $view->grid = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function getEntry($id = null) {
        $view = View("admin.{$this->layout}.entry")->with('title', $this->title);

        $entry = null;
        if ($id) {
            $entry = $this->objModel->find($id);

            $entry->descricao = urldecode($entry->descricao);
        }

        $view->entry = $entry;

        return $view;
    }

    public function postIndex(Requests\LaudoMedicoRequest $request) {
        $save = $this->objModel->saveData($request->all());

        return redirect("admin/{$this->layout}/list");
    }
}
