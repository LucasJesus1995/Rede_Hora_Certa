<?php

namespace App\Http\Controllers;

use App\Http\Helpers\UsuarioHelpers;
use App\LinhaCuidado;
use App\LinhaCuidadoMedicamentos;
use App\Procedimentos;
use App\Profissionais;
use Illuminate\Http\Request;
use App\Cid;
use App\LinhaCuidadoCids;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class LinhaCuidadoController extends Controller
{
    public $model = 'LinhaCuidado';

    use TraitController;

    public function __construct() {
        $this->title = "app.linha-cuidado";

        parent::__construct();
    }
    
    public function getProfissionais($linha_cuidado){
        $res = Profissionais::ComboByLinhaCuidado($linha_cuidado);
        $data['status'] = 1;

        if($res){
            $data['data'] = $res;
        }

        return json_encode($data);
    }

    public function getProcedimentos($linha_cuidado){
        $res = Procedimentos::ComboByLinhaCuidado($linha_cuidado);
        $data['status'] = 1;

        if($res){
            $data['data'] = $res;
        }

        return json_encode($data);
    }

    public function getProcedimentosPrincipais($linha_cuidado){
        $res = Procedimentos::ComboPrincipaisByLinhaCuidado($linha_cuidado);
        $data['status'] = 1;

        if($res){
            $data['data'] = $res;
        }

        return json_encode($data);
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('id','nome','abreviacao', 'ordem', 'ativo')
            ->orderBy('ativo','desc')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->oRwhere('nome', 'LIKE', "%{$params}%");
            $sql->oRwhere('abreviacao', 'LIKE', "%{$params}%");
        }

        $view->grid = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\LinhaCuidadoRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }

    public function getArena($id = null){
        $data['status'] = false;

        if($id){
            $key = 'linha-cuidado-combo'.$id."-".UsuarioHelpers::getNivel();

            if (!Cache::has($key)) {
                $linhas = LinhaCuidado::ByArena($id);

                if (count($linhas))
                    Cache::put($key, $linhas, CACHE_DAY);
            }else{
                $linhas = Cache::get($key);
            }

            $data['data'] = $linhas;
            $data['status'] = !empty($data['data']);
        }

        return json_encode($data);
    }

    public function getMedicamentos($linha_cuidado){
        $view = View("admin.{$this->layout}.medicamentos");

        $view->grid = LinhaCuidado::getMedicamentosByLinhaCuidado($linha_cuidado);
        $view->linha_cuidado = LinhaCuidado::get($linha_cuidado);
        
        return $view;
    }

    public function getCids($linha_cuidado) {
        $view = View("admin.{$this->layout}.cids");

        $view->cids = Cid::getAll();
        $view->linha_cuidado_cids = LinhaCuidadoCids::getByLinhaCuidado($linha_cuidado);
        $view->linha_cuidado = $linha_cuidado;
        return $view;
    }
    
    public function postMedicamentos(){
        $data = Input::all();

        $linha_cuidado_medicamento = LinhaCuidadoMedicamentos::find($data['id']);
        $linha_cuidado_medicamento->valor = $data['valor'];
        $linha_cuidado_medicamento->default = $data['default'];
        $linha_cuidado_medicamento->save();

        return null;
    }

    public function postCids(){
        $dados = Input::all();

        $procedimento_cids = LinhaCuidadoCids::saveData($dados);

        return [];
    }
}
