<?php

namespace App\Http\Controllers;

use App\Permission;
use App\PermissionRole;
use App\Roles;
use App\Tipos;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Psy\Util\Json;


class PerfilController extends Controller
{
    public $model = 'Roles';

    use TraitController;

    public function __construct()
    {
        $this->title = "app.perfil";

        parent::__construct();
    }

    public function getIndex() {
        return redirect("admin/perfil/list");
    }


    public function getView($id)
    {
        $view = View("admin.{$this->layout}.view");

        $view->roles = Roles::getRolesPermission($id);
        $view->perfil = $id;

        return $view;
    }

    public function postView()
    {
        $return = array();
        $return['status'] = true;

        try {
            $data = Input::all();

            PermissionRole::savePermissionRole($data);

        } catch (\Exception $e) {
            $return['status'] = false;
            $return['message'] = $e->getMessage();
        }

        return Json::encode($return);
    }

    public function getGrid()
    {
        $view = View("admin.{$this->layout}.grid");

        $view->grid = Roles::select('id', 'role_title', 'role_slug', 'ativo')
            ->orderBy('ativo', 'desc')
            ->orderBy('id', 'asc')
            ->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\RolesRequest $request)
    {
        $data = $request->all();
       $save = $this->_saveData($this->objModel, $request);

        if (empty($data['id']) && !empty($save['id'])) {
            $permissions = Permission::lists('id','id')->toArray();

            foreach($permissions AS $id){
                PermissionRole::savePermissionRole(array('permission'=>$id, 'perfil' => $save['id']));
            }
        }

        return redirect("admin/perfil/list");
    }


}
