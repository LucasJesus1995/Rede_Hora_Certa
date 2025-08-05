<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Profissionais;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\Console\Input\Input;
use Maatwebsite\Excel\Facades\Excel;

class ProfissionaisController extends Controller
{
    public $model = 'Profissionais';

    use TraitController;

    public function __construct() {
        $this->title = "app.profissionais";

        parent::__construct();
    }

    public function getCombo($type = null){
        $res = Profissionais::Combo($type);
        $data['status'] = 1;

        if($res){
            $data['data'] = $res;
        }

        return json_encode($data);
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql =  $this->objModel->select('id','nome','ativo','cns','cpf', 'cro', 'type')
            ->orderBy('id','desc');

        $perfil = strtoupper(\Illuminate\Support\Facades\Input::get('perfil', null));
        if($perfil) {
            $sql->where('type', '=', $perfil);
        }

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if($params) {
            $sql->whereRaw("(nome LIKE '%{$params}%' OR cns LIKE '%{$params}%' OR cpf LIKE '%{$params}%' OR id LIKE '%{$params}%')");
        }

        $view->grid  = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function getEntry($id = null) {
        $view = View("admin.{$this->layout}.entry")->with('title', $this->title);

        $entry = null;
        if ($id) {
            $entry = $this->objModel->find($id);

            $entry['linha_cuidado'] = array_values(\App\ProfissionaisLinhaCuidado::where('profissional', $id)->get()->lists('linha_cuidado')->toArray());
            $entry['cbo'] = array_values(\App\ProfissionaisCbo::where('profissional', $id)->get()->lists('cbo')->toArray());
            $entry['arena'] = array_values(\App\ProfissionaisArenas::where('profissional', $id)->get()->lists('arena')->toArray());
        }

        $view->entry = $entry;

        return $view;
    }

    public function postIndex(Requests\ProfissionaisRequest $request) {
        $save = $this->objModel->saveData($request->all());

        return redirect("admin/{$this->layout}/list");
    }

    public function getArena($id = null){
        $data['status'] = false;

        if($id){
            $data['data'] = $this->objModel->ByArena($id);
            $data['status'] = !empty($data['data']);
        }

        return json_encode($data);
    }

    public function getExportXls(){
        $path = PATH_FILE_RELATORIO.'excel/profissionais/';
        Upload::recursive_mkdir($path);

        $filename = "cies-profissionais";

        try {
            Excel::create($filename, function ($excel)  {
                $tipos = Util::TypeProfissional();

                foreach ($tipos as $key => $tipo){
                    $data = Profissionais::select('*')->orderBy('ativo', 'desc')->orderBy('nome','asc')->where('type',$key)->get()->toArray();

                    $excel->sheet(Util::String2DB($tipo), function ($sheet) use ($data) {
                        $sheet->loadView('relatorio.excel.profissionais')->with('relatorio', $data);
                    });
               }
            })->download('xlsx');

        }catch (\Exception $e){
            print("<pre>LINE: ".__LINE__." - Exception: ".print_r($e->getMessage(), 1)."</pre>"); #debug-edersonsandre

        }
    }

}
