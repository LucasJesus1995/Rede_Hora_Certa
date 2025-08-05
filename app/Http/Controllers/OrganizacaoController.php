<?php
/**
 * Created by PhpStorm.
 * User: felipe
 * Date: 04/09/18
 * Time: 17:19
 */

namespace App\Http\Controllers;

use App\Http\Requests;

class OrganizacaoController extends Controller
{
    public $model = 'Organizacao';

    use TraitController;

    public function __construct()
    {
        $this->title = "Organização";

        parent::__construct();
    }

    public function getGrid()
    {
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('procedimento_organizacao.id', 'procedimento_organizacao.codigo', 'procedimento_organizacao.descricao', 'procedimento_sub_grupos.descricao as descricaoSubGrupo')
            ->join('procedimento_sub_grupos', 'procedimento_organizacao.sub_grupo', '=', 'procedimento_sub_grupos.id')
            ->orderBy('id', 'desc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if ($params) {
            $sql->Orwhere('procedimento_organizacao.descricao', 'LIKE', "%{$params}%")
                ->Orwhere('procedimento_organizacao.codigo', 'LIKE', "%{$params}%")
                ->Orwhere('procedimento_sub_grupos.descricao', 'LIKE', "%{$params}%")
                ->Orwhere('procedimento_organizacao.id', '=', $params);

        }

        $view->grid = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\OrganizacaoRequest $request)
    {
        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }

}