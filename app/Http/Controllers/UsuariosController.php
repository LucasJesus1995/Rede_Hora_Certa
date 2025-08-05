<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UsuariosController extends Controller
{
    public $model = 'Usuarios';

    use TraitController;

    public function __construct() {
        $this->title = "app.usuarios";

        parent::__construct();
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql  = $this->objModel->select(['users.id','users.email','users.profile','users.level', 'users.name','users.active','lotes.nome'])
            ->join('lotes', 'lotes.id','=','users.lote')    
            ->orderBy('users.id','desc');

        $perfil = strtoupper(\Illuminate\Support\Facades\Input::get('perfil', null));
        if($perfil) {
            $sql->where('profile', '=', $perfil);
        }

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->whereRaw("(users.name LIKE '%{$params}%' OR users.email LIKE '%{$params}%' OR users.id LIKE '%{$params}%')");
        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\UsuariosRequest $request) {
        $save = $this->objModel->savedata($request->all());

        return redirect("admin/{$this->layout}/list");
    }

    public function getDelete($id) {
        $object =  $this->objModel->find($id);

        if(isset($object->id)) {
            $object->active = 0;
            $object->save();
        }

        return json_encode(['status'=>true]);
    }
}
