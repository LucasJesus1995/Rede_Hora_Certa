<?php

namespace App\Http\Controllers\Admin;

use App\Arenas;
use App\Cbo;
use App\FaturamentoProcedimento;
use App\Http\Controllers\TraitController;
use App\LinhaCuidado;
use App\LinhaCuidadoProcedimentos;
use App\LoteProfissional;
use App\LoteProfissionalCbo;
use App\Lotes;
use App\LotesArena;
use App\LotesLinhaCuidado;
use App\Procedimentos;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Zend\Filter\Digits;

class LoteController extends Controller
{
    public $model = 'Lotes';

    use TraitController;

    public function __construct() {
        $this->title = "app.lotes";

        parent::__construct();
    }

    public function postIndex(Requests\LoteRequest $request)
    {
        $save = $this->objModel->saveData($request->all());

        return redirect("admin/{$this->layout}/list");
    }

    public function getArena($id){
        $view = View("admin.{$this->layout}.arena-list");

        $view->lote =  $lote = Lotes::find($id);

        $view->arenas = Arenas::select("*")->get()->toArray();

        return $view;
    }

    public function postArenaCadastro(){
        $data = Input::all();

        try{
            $return['success'] = true;

            $_lote_arena = LotesArena::getArenaLote($data['lote'], $data['arena']);

            if(!empty($_lote_arena[0])){
                $lote = LotesArena::find($_lote_arena[0]['id']);

                if($data['checked'] == 'false'){
                    $lote->delete();
                }
            }else{
                if($data['checked']) {
                    $lote_arena = new LotesArena();
                    $lote_arena->arena = $data['arena'];
                    $lote_arena->lote = $data['lote'];
                    $lote_arena->save();
                }
            }
        }catch (\Exception $e){
            $return['success'] = false;
        }

        return json_encode($return);
    }

    public function getProfissionais($lote){
        $view = View("admin.{$this->layout}.profissionais");

        $lote = Lotes::find($lote);

        $view->lote = $lote;


        return $view;
    }

    public function getGridProfissionais($lote){
        $view = View("admin.{$this->layout}.grid-profissionais");

        $view->grid = LoteProfissional::getByLote($lote);


        return $view;
    }

    public function getGridProfissionaisCbo($lote_profissionais_id){
        $view = View("admin.{$this->layout}.grid-profissionais-cbo");

        $view->grid = LoteProfissionalCbo::getCbos($lote_profissionais_id);

        return $view;
    }

    public function postProfissionais(){
        $data = Input::all();

        try{
            if(empty($data['profissional']))
                throw new \Exception("Profissional não informado!");

            $lote_profissional = LoteProfissional::getLoteProfissional($data['lote'], $data['profissional']);
            if($lote_profissional)
                throw new \Exception("Profissional já cadastrado neste lote");

            $lote = LoteProfissional::saveData($data['lote'], $data['profissional']);

            $return['success'] = true;

        }catch (\Exception $e){
            $return['message'] =  $e->getMessage();
            $return['success'] = false;
        }

        return json_encode($return);
    }

    public function getListCbos($lote_profissional){
        $view = View("admin.{$this->layout}.list-cbos");

        $lote_profissional = LoteProfissional::find($lote_profissional);

        $cbos = Cbo::orderBy('nome','asc')->get();
        $_cbos = [];
        if($cbos){
            foreach ($cbos AS $cbo){
                $_cbos[$cbo->id] = $cbo->codigo ." - ".$cbo->nome;
            }
        }

        $view->lote = $lote_profissional->lote;
        $view->lote_profissional = $lote_profissional;
        $view->cbos = $_cbos;

        return $view;
    }

    public function postLoteProfissionaisCbo(){
        $data = Input::all();

        try{
            if(empty($data['profissional']))
                throw new \Exception("Profissional não informado!");

            $lote_profissional = LoteProfissional::getLoteProfissional($data['lote'], $data['profissional']);
            if(empty($lote_profissional))
                throw new \Exception("Não foi encontrado nenhum vinculoo do profiossional a este lote!");

            if($data['checked']){
                $lote_profissional_cbo = LoteProfissionalCbo::saveData($lote_profissional->id, $data['cbo']);
            }else{
                $lote_profissional_cbo = LoteProfissionalCbo::getLoteProfissionalCbo($lote_profissional->id, $data['cbo']);
                $lote_profissional_cbo->delete();
            }

            $return['success'] = true;

        }catch (\Exception $e){
            $return['message'] =  $e->getMessage();
            $return['success'] = false;
        }

        return json_encode($return);
    }

    public function postProfissionaisDelete(){
        $data = Input::all();

        try{
            $lote_profissional = LoteProfissional::find($data['lote_profissional']);
            if($lote_profissional->count()){
                $lote_profissional->delete();
            }

            $return['success'] = true;

        }catch (\Exception $e){
            $return['message'] =  $e->getMessage();
            $return['success'] = false;
        }

        return json_encode($return);
    }

}
