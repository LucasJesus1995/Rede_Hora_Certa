<?php

namespace App\Http\Controllers;

use App\Agendas;
use App\Atendimentos;
use App\Cidades;
use App\Http\Helpers\Importacao\ImportacaoPacienteCorrecao;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Http\Rules\CartaoPacientesCIES;
use App\ImportacaoAgenda;
use App\Pacientes;
use App\User;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PacientesController extends Controller
{
    public $model = 'Pacientes';

    use TraitController;

    public function __construct()
    {
        $this->title = "app.pacientes";

        parent::__construct();
    }

    public function getDadosCorrecaoImportacao()
    {
        $view = View("admin.{$this->layout}.dados-correcao-importacao");

        return $view;
    }

    public function postDadosCorrecaoImportacao(Requests\Admin\DadosCorrecaoImportacaoPacienteRequest $request)
    {
        try {

            $_log = null;
            $_log['start'] = date('Y-m-d H:i:s');
            $_log['user']['codigo'] = Auth::user()->id;
            $_log['user']['nome'] = Auth::user()->name;
            $_log['user']['email'] = Auth::user()->email;
            $_log['success'] = null;
            $_log['error'] = null;

            $_upload = new Upload();

            $filename = $_upload->importacao_upload_paciente($request->file('file'));

            $importacao_agenda = new ImportacaoAgenda();
            $importacao_agenda->file = $filename;
            $importacao_agenda->user = Auth::user()->id;
            $importacao_agenda->tipo = 4;
            $importacao_agenda->save();

            $content = File::get(PATH_UPLOAD . "importacao/paciente/" . $filename);
            if (!empty($content)) {

                $content = explode("\r\n", trim($content));

                $importacao_agenda->records = count($content);
                $importacao_agenda->data = serialize($content);
                $importacao_agenda->log = serialize($_log);
                $importacao_agenda->save();

                if (!is_array($content)) {
                    throw new \Exception("Arquivo formato inválido!");
                }

                $content = array_map('trim', $content);

                foreach ($content AS $line_number => $row) {
                    if (empty($row)) {
                        continue;
                    }

                    try {
                        $_data = explode(';', $row);

                        if (!is_array($_data)) {
                            continue;
                        }

                        $paciente = ImportacaoPacienteCorrecao::import($_data, $importacao_agenda, $line_number + 1);

                        if (!empty($paciente->id)) {
                            $_log['success'][] = $_data[1];
                        }
                    } catch (\Exception $exception) {
                        $_log['error'][] = [
                            'cns' => trim($row[1]),
                            'error' => $exception->getMessage()
                        ];
                    }
                }

            }

            $_log['success'][] = $paciente->cns;

        } catch (\Exception $e) {

            $_log['error'][] = $e->getMessage();

            $_log['end'] = date('Y-m-d H:i:s');

            $importacao_agenda->imported = count($_log['success']);
            $importacao_agenda->failure = count($_log['error']);

            $importacao_agenda->log = serialize($_log);
            $importacao_agenda->save();

            return redirect()->back()->withInput()->withErrors(
                [
                    '<b>Não foi possivel processar a importação</b>',
                    '- ' . $e->getMessage(),
                ]
            );
        }

        $_log['end'] = date('Y-m-d H:i:s');

        $importacao_agenda->imported = count($_log['success']);
        $importacao_agenda->failure = count($_log['error']);

        $importacao_agenda->log = serialize($_log);
        $importacao_agenda->save();

        return redirect('/admin/pacientes/dados-correcao-importacao')->with('status', $importacao_agenda->failure == 0 ? "Arquivo importado com successo!" : "Houve falha em alguns dados de importação!");
    }

    public function getDadosCorrecaoImportacaoGrid()
    {
        $view = View("admin.{$this->layout}.dados-correcao-importacao-list")->with('title', $this->title);
        $data = array();

        $sql = ImportacaoAgenda::orderBy('id', 'desc');
        $sql->where('tipo', 4);
        if (User::getPerfil() == 14 && !in_array(User::getId(), [110])) {
            $sql->where('user', User::getId());
            $sql->limit(20);
        } else {
            $sql->limit(100);
        }

        $importacao = $sql->get();

        foreach ($importacao AS $row) {
            $log = !empty($row->data) ? @unserialize($row->data) : null;

            $data[] = array(
                'date' => substr($row->created_at, 8, 2) . "/" . substr($row->created_at, 5, 2) . "/" . substr($row->created_at, 0, 4),
                'time' => substr($row->created_at, 11, 2) . ":" . substr($row->created_at, 14, 2),
                'data' => !empty($log['data']) ? serialize($log['data']) : null,
                'total' => $row->records,
                'log' => $row->log,
                'insert' => $row->imported,
                'error' => $row->failure,
                'id' => $row->id,
            );
        }

        $view->grid = $data;

        return $view;
    }

    public function getDadosCorrecao()
    {
        $view = View("admin.{$this->layout}.dados-correcao");

        $view->pacientes = Pacientes::getDadosInvalidos();

        return $view;
    }

    public function getPrintCardCies($cpf)
    {
        try {
            $view = View("admin.{$this->layout}.card-cies");

            $cartaoPacienteCIES = new CartaoPacientesCIES();
            $paciente = Pacientes::getCPF($cpf);

            $view->paciente = $paciente;
            $view->cartao = $cartaoPacienteCIES->gerarNumeroCartao($paciente);

            $contents = $view->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($contents);

            $paper_size = array(0, 0, 486, 306);
            $dompdf->set_paper($paper_size, 'portrait');

            $dompdf->render();
            $dompdf->stream($cpf, array("Attachment" => false));

        } catch (\Exception $e) {
            print("<pre>LINE: " . __LINE__ . " - " . print_r($e->getLine(), 1) . "</pre>"); #debug-edersonsandre
            print("<pre>LINE: " . __LINE__ . " - " . print_r($e->getFile(), 1) . "</pre>"); #debug-edersonsandre
            exit("<pre>LINE: " . __LINE__ . " - " . print_r($e->getMessage(), 1) . "</pre>"); #debug-edersonsandre
        }
        die;
    }

    public function getEntry($id = null)
    {
        $view = View("admin.{$this->layout}.entry")->with('title', $this->title);

        $entry = null;
        if ($id) {
            $entry = $this->objModel->find($id);
            $entry->nascimento = Util::DB2User($entry->nascimento);

            $cidades = Cidades::find($entry->cidade);
            if (!empty($cidades->estado)) {
                $entry->estado = $cidades->estado;
            }

        }

        $view->entry = $entry;

        return $view;
    }

    public function getGrid(Request $request)
    {
        $view = View("admin.{$this->layout}.grid");

        $sql = $this->objModel->select('id', 'nome', 'nome_social', 'ativo', 'cpf', 'sexo', 'nascimento', 'estado_civil', 'cns', 'cidade', 'celular','telefone_comercial','telefone_residencial','telefone_contato')
            ->orderBy('id', 'desc')
            ->limit(30);

        $q = $request->get('q');
        $field = $request->get('field');
        if ($q || $field) {

            if ($field == 'cns') {
                $sql->where($field, $q);
            }

            if ($field == 'cpf') {
                $sql->where($field, $q);
            }

            if ($field == 'nome') {
                $sql->where($field, 'LIKE', "%{$q}%");
            }
        }

        $view->grid = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function postIndex(Requests\PacientesRequest $request)
    {
        $save = $this->objModel->saveData($request->all());

        return redirect("admin/{$this->layout}/list");
    }

    public function getByCns($cns)
    {
        $data = array();

        if (strlen($cns) > 2) {
            $paciente = Pacientes::select('*')
                ->where('cns', $cns)
                ->limit(1)
                ->orderBy('id', 'DESC')
                ->get()
                ->toArray();

            $data = !empty($paciente[0]['id']) ? $paciente[0] : array();
        }

        return json_encode($data);
    }


    public function getSearch($paciente = null)
    {
        if (strlen($paciente) > 2) {
            $pacientes = Pacientes::Search($paciente, 5);

            $json = $pacientes;
        }

        return json_encode(!empty($json) ? $json : array());
    }

    public function getProntuario($paciente)
    {
        $view = View("admin.pacientes.prontuario.index");

        $view->paciente = Pacientes::get($paciente);

        return $view;
    }


    public function getProntuarioAgenda($paciente)
    {
        $view = View("admin.pacientes.prontuario.agenda");

        $view->agendas = Agendas::getByPaciente($paciente);

        return $view;
    }

    public function getAnexos($paciente)
    {
        $view = View("admin.pacientes.prontuario.anexos.listagem");

        $view->anexos = Atendimentos::getAnexosHistorico($paciente);

        return $view;
    }

}
