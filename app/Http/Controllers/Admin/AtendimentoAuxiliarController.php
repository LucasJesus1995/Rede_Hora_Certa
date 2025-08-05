<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AtendimentoAuxiliarController extends Controller
{
    public $model = 'Atendimentos';

    public function __construct()
    {
        parent::__construct();
    }

    public function getSinaisVitais($atendimento){
        $view = View("admin.atendimentos.auxiliar.sinais-vitais");

        return $view;
    }


}
