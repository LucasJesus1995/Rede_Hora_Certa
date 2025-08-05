<?php

namespace App\Http\Controllers\Admin;

use App\ContratoLotes;
use App\ContratoProcedimentos;
use App\Contratos;
use App\Http\Controllers\TraitController;
use App\Http\Requests\Admin\ContratoRequest;
use App\Http\Controllers\Controller;
use App\Lotes;
use App\LotesArena;
use Illuminate\Http\Request;


class ContratoController extends Controller
{
    public $model = 'Contratos';

    use TraitController;

    public function __construct()
    {
        $this->title = "app.contratos";

        parent::__construct();
    }

    public function postIndex(ContratoRequest $request)
    {
        $save = $this->objModel->saveData($request->all());

        return redirect("admin/{$this->layout}/list");
    }

    public function getEntry($id = null)
    {
        $view = View("admin.{$this->layout}.entry")->with('title', $this->title);

        $entry = null;
        if ($id) {
            $entry = $this->objModel->find($id);

            $entry['lotes'] = array_values(\App\ContratoLotes::where('contrato', $id)->get()->lists('lote')->toArray());
        }

        $view->entry = $entry;

        return $view;
    }

    public function getProcedimentos($contrato)
    {
        $view = View("admin.{$this->layout}.procedimentos")->with('title', $this->title);

        $contrato = Contratos::find($contrato);
        $lotes = ContratoLotes::getLotes($contrato->id);

        $view->contrato = $contrato;
        $view->lotes = $lotes;

        return $view;
    }

    public function postProcedimentoContrato(Request $request)
    {
        $data = $request->all();
        $return['success'] = false;
        
        try {
            if (!empty($data['valor_unitario']) && !empty($data['contrato']) && !empty($data['procedimento'])) {

                $contrato_procedimentos = new ContratoProcedimentos();
                $contrato_procedimentos->saveData($data);
            }

            $return['success'] = true;
        } catch (\Exception $e) {
            $return['success'] = false;
            print("<pre>LINE: " . __LINE__ . " - " . print_r($e->getLine(), 1) . "</pre>"); #debug-edersonsandre
            exit("<pre>LINE: " . __LINE__ . " - " . print_r($e->getMessage(), 1) . "</pre>"); #debug-edersonsandre
        }

        return $return;
    }

}
