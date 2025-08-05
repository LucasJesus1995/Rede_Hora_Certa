<?php

namespace App\Http\Controllers;

use App\Tipos;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class TipoController extends Controller
{
    public $model = 'Tipos';

    use TraitController;

    public function __construct() {
        $this->title = "app.tipos";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $view->grid  = Tipos::select('id','nome','ativo')
            ->orderBy('id','desc')
            ->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\TiposRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}
