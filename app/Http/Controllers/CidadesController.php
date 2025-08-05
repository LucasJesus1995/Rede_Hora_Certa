<?php

namespace App\Http\Controllers;

use App\Cidades;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CidadesController extends Controller{

    public function getByEstado($estado = null){
        $data['status'] = false;

        if($estado){
            $data['status'] = true;
            $data['data'] = Cidades::Combo($estado);
        }

        return json_encode($data);
    }

}
