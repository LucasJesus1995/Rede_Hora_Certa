<?php

namespace App\Http\Controllers\Admin;

use App\ArenaEquipamentos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitController;
use App\Http\Requests\Admin\ArenaEquipamentosRequest;
use App\Http\Requests\ArenasRequest;
use Illuminate\Support\Facades\Input;

class ArenaEquipamentosController extends Controller
{
    public $model = 'ArenaEquipamentos';

    use TraitController;

    public function __construct() {
        $this->title = "Arena Equipamentos";

        parent::__construct();
    }

    public function postIndex(ArenaEquipamentosRequest $request) {
        $this->objModel->saveData($request->all());

        return redirect("admin/{$this->layout}/list");
    }
    
    public function getArena($arena){
        $res = ArenaEquipamentos::getByArena($arena);
        $data['status'] = 1;

        if($res){
            $data['data'] = $res;
        }

        return json_encode($data);
    }
    
}
