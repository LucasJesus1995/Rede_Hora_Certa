<?php

namespace App\Http\Controllers;

use App\AnamnesePerguntas;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AnamnesePerguntasController extends Controller
{
    public $model = 'AnamnesePerguntas';

    use TraitController;

    public function __construct() {
        $this->title = "app.anamnese-perguntas";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = AnamnesePerguntas::select('id','nome','tipo_resposta', 'multiplas','ativo', 'cid')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->oRwhere('nome', 'LIKE', "%{$params}%")
                ->oRwhere('id', '=', $params);

        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\AnamnesePerguntasRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
