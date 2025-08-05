<?php

namespace App\Http\Controllers\Admin\Cirugia;

use App\CirugiaLinhaCuidado;
use App\CirugiaLinhaCuidadoProcedimentos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitController;
use App\Procedimentos;
use App\Tipos;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;


class LinhaCuidadoController extends Controller
{
    public $model = 'CirugiaLinhaCuidado';

    use TraitController;

    public function __construct() {
        $this->title = "app.linha_cuidado_cirugia";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $view->grid  = $this->objModel->select('id','nome','ativo')
            ->orderBy('id','desc')
            ->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function getProcedimentos($id){
        $view = View("admin.{$this->layout}.procedimentos");

        $procedimentos  = Procedimentos::select(
            'procedimentos.id as procedimento',
            'procedimentos.nome as procedimentos_nome',
            'cirugia_linha_cuidado_procedimentos.cirugia_linha_cuidado as linha_cuidado',
            'cirugia_linha_cuidado_procedimentos.id as cirugia_linha_cuidado_procedimentos_id',
            'cirugia_linha_cuidado_procedimentos.qtd as cirugia_linha_cuidado_procedimentos_qtd'

        )
            ->leftJoin('cirugia_linha_cuidado_procedimentos', function($leftJoin) use($id)
            {
                $leftJoin->on('cirugia_linha_cuidado_procedimentos.procedimento','=','procedimentos.id')
                    ->where('cirugia_linha_cuidado_procedimentos.cirugia_linha_cuidado', '=',$id );


            })
            ->where('procedimentos.ativo' , true)
            ->orderBy('procedimentos.nome','asc')
            ->get();

        $view->procedimentos = $procedimentos;
        $view->linha_cuidado = $id;

        //exit("<pre>LINE: ".__LINE__." - ".print_r($procedimentos->toArray(), 1)."</pre>"); #debug-edersonsandre

        return $view;
    }

    public function postProcedimentos(){
        $data = Input::all();

        CirugiaLinhaCuidadoProcedimentos::saveCirugiaProcedimento($data);

        return json_encode(['sucess'=> 1]);
    }

    public function postDeleteProcedimentos(){
        $data = Input::all();

        CirugiaLinhaCuidadoProcedimentos::deleteCirugiaProcedimento($data);

        return json_encode(['sucess'=> 1]);
    }

    public function postIndex(Requests\CirugiaLinhaCuidadoRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
