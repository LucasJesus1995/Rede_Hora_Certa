<?php

namespace App\Http\Controllers;

use App\Agendas;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\View\View;


class ImpressaoController extends Controller
{

    public function getListaAtendimento(){
        $view = View("admin.impressao.lista-atendimento");

        return $view;
    }

}
