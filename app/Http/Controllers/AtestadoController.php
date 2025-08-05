<?php

namespace App\Http\Controllers;

use App\Atendimentos;
use App\Atestado;
use App\Http\Controllers\TraitController;
use App\Http\Helpers\Util;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AtestadoController extends Controller
{
    public $model = 'Atestado';

    use TraitController;

    public function __construct()
    {
        $this->title = "Atestado";

        parent::__construct();
    }

    public function getGrid()
    {
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select(['atestado.id', 'atestado.atendimento', 'atestado.empresa', 'atestado.hora_chegada', 'atestado.hora_saida', 'atestado.tempo_repouso', 'atestado.cid', 'cid.descricao', 'cid.codigo'])
            ->join('cid', 'cid.id', '=', 'atestado.cid')
            ->orderBy('id', 'asc');

        $params = strtoupper(\Illuminate\Support\Facades\Input::get('q', null));
        if ($params) {
            $sql->Orwhere('atestado.atendimento', 'LIKE', "%{$params}%")
                ->Orwhere('atestado.empresa', 'LIKE', "%{$params}%")
                ->Orwhere('atestado.id', '=', $params);

        }

        $view->grid = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function getPrintAtestado($id)
    {
        $view = View("admin.{$this->layout}.print-atestado");
        $view->atestado = null;

        $atestado = Atestado::searchAtestado($id);
        $view->pronome_tratamento = '';

        if($atestado){
            $view->atestado = $atestado;

            $view->atestado[0]['data'] = Util::DBTimestamp2UserDate($view->atestado[0]['data']);
            $view->atestado[0]['pacienteNascimento'] = Util::DB2User($view->atestado[0]['pacienteNascimento']);

            if($atestado[0]['sexo'] == 1)
                $view->pronome_tratamento = 'o Sr.';
            else
                $view->pronome_tratamento = 'a Sra.';

            $contents = $view->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($contents);
            $dompdf->setPaper('A4');
            $dompdf->render();

            $nome_arquivo = "atestado-" . $atestado[0]['id'] . "-" . $atestado[0]['atendimento'];
            $dompdf->stream($nome_arquivo, array("Attachment" => false));
        }

        return $view;
    }

    public function postIndex(Requests\AtestadoRequest $request)
    {
        $atendimento = explode(' - ', $request->get('atendimento'));
        $request->merge(['atendimento' => $atendimento[0]]);

        $save = $this->_saveData($this->objModel, $request);

        return redirect("admin/{$this->layout}/list");
    }

}
