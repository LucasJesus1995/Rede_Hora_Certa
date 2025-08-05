<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MedicamentosController extends Controller
{
    public $model = 'Medicamentos';

    use TraitController;

    public function __construct() {
        $this->title = "app.medicamentos";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql  = $this->objModel->select('id','nome','ativo')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->oRwhere('nome', 'LIKE', "%{$params}%")
                ->oRwhere('id', '=', $params);

        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function getEntry($id = null) {
        $view = View("admin.{$this->layout}.entry")->with('title', $this->title);

        $entry = null;
        if ($id) {
            $entry = $this->objModel->find($id);

            $entry['linha_cuidado'] = array_values(\App\LinhaCuidadoMedicamentos::where('medicamento', $id)->get()->lists('linha_cuidado')->toArray());
        }

        $view->entry = $entry;

        return $view;
    }

    public function postIndex(Requests\MedicamentosRequest $request) {
        $save = $this->objModel->saveData($request->all());

        return redirect("admin/{$this->layout}/list");
    }
}
