<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Cid;
use App\ProcedimentoCids;
use Illuminate\Support\Facades\Input;

class ProcedimentosMedicosController extends Controller
{
    public $model = 'Procedimentos';

    use TraitController;

    public function __construct()
    {
        $this->title = "Procedimentos Médicos (Valores)";

        parent::__construct();

        $this->layout = "procedimentos-medicos";
    }

    public function getIndex()
    {
        return redirect("admin/procedimentos-medicos/list");
    }

    public function getGrid()
    {
        $view = View("admin.procedimentos-medicos.grid");

        $sql = $this->objModel->select('id', 'nome', 'ativo', 'valor_medico', 'multiplicador_medico','sus')
            ->orderBy('ativo', 'desc')
            ->orderBy('id', 'desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if ($params) {
            $sql->oRwhere('nome', 'LIKE', "%{$params}%")
                ->oRwhere('id', '=', $params);

        }

        $view->grid = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function getEntry($id = null)
    {
        $view = View("admin.procedimentos-medicos.entry")->with('title', $this->title);

        $entry = null;
        if ($id) {
            $entry = $this->objModel->find($id);

            $entry['linha_cuidado'] = array_values(\App\LinhaCuidadoProcedimentos::where('procedimento', $id)->get()->lists('linha_cuidado')->toArray());
        }

        $view->entry = $entry;

        return $view;
    }

    public function postIndex(Requests\ProcedimentosMedicosRequest $request)
    {
        $data = $request->all();
        $data['valor_medico'] = str_replace(" ", "", $data['valor_medico']);

        $model = $this->objModel->find($data['id']);
        $model->multiplicador_medico = $data['multiplicador_medico'];
        $model->valor_medico = $data['valor_medico'];
        $model->save();

        return redirect("admin/{$this->layout}/list");
    }


}
