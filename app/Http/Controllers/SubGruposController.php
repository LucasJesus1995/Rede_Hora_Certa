<?php
/**
 * Created by PhpStorm.
 * User: felipe
 * Date: 04/09/18
 * Time: 16:41
 */

namespace App\Http\Controllers;

use App\Http\Requests;

class SubGruposController extends Controller
{
    public $model = 'SubGrupos';

    use TraitController;

    public function __construct() {
        $this->title = "Sub Grupos";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('procedimento_sub_grupos.id','procedimento_sub_grupos.codigo','procedimento_grupos.codigo AS codigo_grupo','procedimento_sub_grupos.descricao','procedimento_grupos.descricao as descricaoGrupo')
            ->join('procedimento_grupos','procedimento_sub_grupos.grupo' , '=', 'procedimento_grupos.id')
            ->orderBy('id','desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->Orwhere('procedimento_sub_grupos.descricao', 'LIKE', "%{$params}%")
                ->Orwhere('procedimento_sub_grupos.codigo', 'LIKE', "%{$params}%")
                ->Orwhere('procedimento_grupos.descricao', 'LIKE', "%{$params}%")
                ->Orwhere('procedimento_sub_grupos.id', '=', $params);

        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\SubGruposRequest $request) {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }
}