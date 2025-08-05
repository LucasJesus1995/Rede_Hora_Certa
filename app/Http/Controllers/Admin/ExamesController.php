<?php

namespace App\Http\Controllers\Admin;

use App\ExamesLinhaCuidado;
use App\Http\Controllers\TraitController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExamesRequest;
use Illuminate\Http\Request;

class ExamesController extends Controller
{
    public $model = 'Exames';

    use TraitController;

    public function __construct()
    {
        $this->title = "Exames";

        parent::__construct();
    }

    public function postIndex(ExamesRequest $request)
    {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }

    public function getLinhaCuidado($exame)
    {
        $view = View("admin.{$this->layout}.linha_cuidado");

        $view->linha_cuidado_exames = ExamesLinhaCuidado::getLinhaCuidado($exame);
        $view->exame = $exame;

        return $view;
    }

    public function postLinhaCuidado(Request $request)
    {
        $response['success'] = false;
        try {
            $res = ExamesLinhaCuidado::saveLinhaCuidadoExame($request->get('exame'), $request->get('linha_cuidado'), $request->get('acao'));

            $response['success'] = true;
        } catch (\Exception $e) {
            $response['message'] = false;
        }

        return $response;
    }

}
