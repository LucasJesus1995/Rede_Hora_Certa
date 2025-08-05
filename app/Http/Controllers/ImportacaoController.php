<?php

namespace App\Http\Controllers;

use App\Agendas;
use App\ArenaEquipamentos;
use App\Arenas;
use App\ArenasLinhaCuidado;
use App\Http\Helpers\Importacao\ImportacaoSIGAPDF;
use App\Http\Helpers\Importacao\Remarcacao;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Http\Requests\ImportacaoAgendaPDFRequest;
use App\Importacao;
use App\ImportacaoAgenda;
use App\LinhaCuidado;

use App\Http\Requests;
use App\LotesArena;
use App\OfertaLoteLinhaCuidado;
use App\Pacientes;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Smalot\PdfParser\Parser;
use SuperClosure\SerializableClosure;


class ImportacaoController extends Controller
{
    public $model = 'Importacao';


    public function __construct()
    {
        $this->title = "app.importacao";

        parent::__construct();
    }

    public function getAgendaOfertaPdf()
    {
        $this->title = "Importação Agenda (Oferta)";
        $view = View("admin.{$this->layout}.agenda-oferta-pdf.index")->with('title', $this->title);

        return $view;
    }

    public function getAgendaPdf()
    {
        $this->title = "Importação Agenda (PDF)";
        $view = View("admin.{$this->layout}.agenda-pdf.index")->with('title', $this->title);

        return $view;
    }


    public function getAgendaPdfGrid()
    {
        $view = View("admin.{$this->layout}.agenda-pdf.list")->with('title', $this->title);
        $data = array();

        $sql = ImportacaoAgenda::orderBy('id', 'desc');
        $sql->where('tipo', 3);
        if (User::getPerfil() == 14 && !in_array(User::getId(), [110])) {
            $sql->where('user', User::getId());
            $sql->limit(40);
        } else {
            $sql->limit(200);
        }
        $importacao = $sql->get();

        foreach ($importacao AS $row) {
            $log = !empty($row->data) ? @unserialize($row->data) : null;

            $arenas = !empty($log['arena']) ? Arenas::get($log['arena']) : null;
            $linha_cuidado = !empty($log['linha_cuidado']) ? LinhaCuidado::get($log['linha_cuidado']) : null;

            $data[] = array(
                'date' => substr($row->created_at, 8, 2) . "/" . substr($row->created_at, 5, 2) . "/" . substr($row->created_at, 0, 4),
                'time' => substr($row->created_at, 11, 2) . ":" . substr($row->created_at, 14, 2),
                'data' => !empty($log['data']) ? serialize($log['data']) : null,
                'arena' => !empty($arenas['nome']) ? $arenas['nome'] : null,
                'linha_cuidado' => !empty($linha_cuidado['nome']) ? $linha_cuidado['nome'] : null,
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

    public function postAgendaPdf(ImportacaoAgendaPDFRequest $request)
    {
        $params = $request->all();
        if (!empty($params['file'])) {
            unset($params['file']);
        }

        $_log = null;
        $_log['start'] = date('Y-m-d H:i:s');
        $_log['user']['codigo'] = Auth::user()->id;
        $_log['user']['nome'] = Auth::user()->name;
        $_log['user']['email'] = Auth::user()->email;
        $_log['params'] = $params;
        $_log['success'] = null;
        $_log['error'] = null;

        $importacao_agenda = new ImportacaoAgenda();
        $importacao_agenda->user = Auth::user()->id;
        $importacao_agenda->tipo = 3;

        try {

            $parser = new Parser();
            $path_file = Upload::agendaPDFSIGA($request->file('file'));

            $data = ImportacaoSIGAPDF::import($parser->parseFile($path_file));
            $_log['data'] = $data;

            if (is_array($data) && !empty($data[0])) {

                $_data['data_agendamento'] = $request->get('data');
                $_data['arena'] = $request->get('arena');
                $_data['linha_cuidado'] = $request->get('linha_cuidado');
                $_data['equipamento'] = $request->get('equipamento');
                $_data['tipo_atendimento'] = $request->get('tipo_atendimento');
                $_data['medico'] = $request->get('medico');
                $_data['data'] = $data;

                $data_agenda = Carbon::createFromFormat("d/m/Y", $request->get('data'));

                $importacao_agenda->data = serialize($_data);
                $importacao_agenda->file = $request->file('file')->getClientOriginalName();
                $importacao_agenda->records = count($data);
                $importacao_agenda->save();

                foreach ($data AS $k => $row) {

                    try {
                        $paciente = self::getPaciente(trim($row['cns']), $row['nome'], $row['estabelecimento']);

                        $agenda = new Agendas();
                        $agenda->arena = $request->get('arena');
                        $agenda->linha_cuidado = $request->get('linha_cuidado');
                        $agenda->arena_equipamento = $request->get('equipamento');
                        $agenda->tipo_atendimento = $request->get('tipo_atendimento');
                        $agenda->medico = $request->get('medico');
                        $agenda->data = $data_agenda->toDateString() . " " . $row['horario'] . ":00";
                        $agenda->paciente = $paciente->id;
                        $agenda->estabelecimento = $row['estabelecimento'];
                        $agenda->import = $importacao_agenda->id."-".($k+1);

                        $agenda->validaAgenda();

                        $agenda->save();
                        $_log['success'][] = $paciente->cns;
                    } catch (\Exception $exception) {
                        $_log['error'][] = [
                            'cns' => trim($row['cns']),
                            'error' => $exception->getMessage()
                        ];

                    }
                }
            }

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

        return redirect('/admin/importacao/agenda-pdf')->with('status', $importacao_agenda->failure == 0 ? "Arquivo importado com successo!" : "Houve falha em alguns dados de importação!");
    }

    public static function getPaciente($cns, $nome = null, $estabelecimento = null)
    {
        $paciente = Pacientes::getByCNSCompleto($cns);

        if (empty($paciente->id)) {
            $paciente = new Pacientes();
            $paciente->cns = $cns;
            $paciente->nome = $nome;
            $paciente->sexo = null;
            $paciente->estabelecimento = $estabelecimento;
            $paciente->save();
        }

        return $paciente;
    }


    public function getAgendaRemarcacao()
    {
        return redirect("/");
        $this->title = "Importação Agenda (Remarcação)";
        $view = View("admin.{$this->layout}.agenda-remarcacao.index")->with('title', $this->title);

        return $view;
    }

    public function postAgendaRemarcacao(Requests\Importacao\ImportacaoAgendaRemarcacaoRequest $request)
    {

        try {
            $file = $request->file('file');

            $path = "uploads/tmp/" . User::getId() . "/agenda-remarcacao/";
            Upload::recursive_mkdir($path);

            $file_name = uniqid() . ".xlsx";
            $file->move($path, $file_name);

            $data = null;
            Excel::load($path . $file_name, function ($reader) use ($data, $request) {

                $results = $reader->get();

                if (
                    empty($results[0]->sus) ||
                    empty($results[0]->horario) ||
                    empty($results[0]->nascimento) ||
                    empty($results[0]->paciente)
                ) {
                    throw new \Exception("Layout inválido!");
                }

                foreach ($results AS $result) {
                    if (strlen(Util::somenteNumeros($result->sus)) < 10) {
                        continue;
                    }

                    $agendamento = Util::Date2DB($request->get('data'));

                    $data[] = [
                        'arena' => $request->get('arena'),
                        'linha_cuidado' => $request->get('linha_cuidado'),
                        'data' => $agendamento,
                        'horario' => $result->horario->format('H:i:s'),
                        'nome' => $result->paciente,
                        'cns' => intval($result->sus),
                        'nascimento' => $result->nascimento->format("Y-m-d"),
                    ];

                }


                if (count($data)) {
                    $params = $request->all();
                    unset($params['file']);
                    unset($params['_token']);

                    $importacao = new Remarcacao($data);
                    $importacao->params = $params;
                    $importacao->process();
                }
            });

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(
                [
                    '<b>Não foi possivel processar a importação</b>',
                    '- ' . $e->getMessage(),
                ]
            );
        }

        return redirect()->back();
    }

    public function getAgendaRemarcacaoList()
    {
        $view = View("admin.{$this->layout}.agenda-remarcacao.list")->with('title', $this->title);
        $data = array();

        $sql = ImportacaoAgenda::orderBy('id', 'desc');
        $sql->where('tipo', 1);
        if (User::getPerfil() == 14 && !in_array(User::getId(), [110])) {
            $sql->where('user', User::getId());
            $sql->limit(20);
        } else {
            $sql->limit(100);
        }

        $importacao = $sql->get();

        foreach ($importacao AS $row) {
            $log = unserialize($row->data);

            $arenas = !empty($log['arena']) ? Arenas::get($log['arena']) : null;
            $linha_cuidado = !empty($log['linha_cuidado']) ? LinhaCuidado::get($log['linha_cuidado']) : null;

            $data[] = array(
                'date' => substr($row->created_at, 8, 2) . "/" . substr($row->created_at, 5, 2) . "/" . substr($row->created_at, 0, 4),
                'time' => substr($row->created_at, 11, 2) . ":" . substr($row->created_at, 14, 2),
                'data' => !empty($log['data']) ? $log['data'] : null,
                'arena' => !empty($arenas['nome']) ? $arenas['nome'] : null,
                'linha_cuidado' => !empty($linha_cuidado['nome']) ? $linha_cuidado['nome'] : null,
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

    public function getAgenda()
    {
        $this->title = "Importação Agenda";
        $view = View("admin.{$this->layout}.agenda")->with('title', $this->title);;

        return $view;
    }

    public function getAgendaGrid()
    {
        $view = View("admin.{$this->layout}.agenda-grid")->with('title', $this->title);
        $data = array();

        $sql = ImportacaoAgenda::orderBy('id', 'desc');
        $sql->where('tipo', 0);
        if (User::getPerfil() == 14 && !in_array(User::getId(), [110])) {
            $sql->where('user', User::getId());
            $sql->limit(50);
        } else {
            $sql->limit(250);
        }

        $importacao = $sql->get();

        $diretorio = PATH_UPLOAD . "importacao/agenda/log/";

        foreach ($importacao AS $row) {
            $file = @file_get_contents($diretorio . $row['file']);

            $log = unserialize($file);

            $arenas = !empty($log['params']['arena']) ? Arenas::get($log['params']['arena']) : null;
            $linha_cuidado = !empty($log['params']['linha_cuidado']) ? LinhaCuidado::get($log['params']['linha_cuidado']) : null;
            $equipamento = !empty($log['params']['equipamento']) ? ArenaEquipamentos::get($log['params']['equipamento']) : null;

            $data[] = array(
                'date' => substr($row->created_at, 8, 2) . "/" . substr($row->created_at, 5, 2) . "/" . substr($row->created_at, 0, 4),
                'time' => substr($row->created_at, 11, 2) . ":" . substr($row->created_at, 14, 2),
                'data' => !empty($log['params']['data']) ? $log['params']['data'] : null,
                'arena' => !empty($arenas['nome']) ? $arenas['nome'] : null,
                'linha_cuidado' => !empty($linha_cuidado['nome']) ? $linha_cuidado['nome'] : null,
                'equipamento' => !empty($equipamento->nome) ? $equipamento->nome : null,
                'total' => $row->records,
                'insert' => $row->imported,
                'error' => $row->failure,
                'file' => $log['params']['file'],
                'log' => $file,
                'id' => $row->id,
            );
        }

        $view->grid = $data;

        return $view;
    }

    public function postAgenda(Requests\ImportacaoAgendaRequest $request)
    {
        $imp = new Importacao();
        $imp->importaAgenda($request);

        return redirect('/admin/importacao/agenda')->with('status', empty($imp->_error) ? "Arquivo importado com successo!" : "Houve falha em alguns dados de importação!");
    }

    public function getFile()
    {
        $file = $_GET['file'];

        if (file_exists($file)) {
            $filename = current(array_reverse(explode("/", $file)));

            $content = file_get_contents($file);

            $headers = ['Content-type' => 'text/plain', 'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)];
            return Response::make($content, 200, $headers);

        } else {
            echo "Arquivo não encontrado!";
        }
    }

    public function getFileLog()
    {
        $file = $_GET['file'];

        if (file_exists($file)) {
            $filename = current(array_reverse(explode("/", $file)));

            $content = file_get_contents($file);

            $headers = ['Content-type' => 'text/plain', 'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)];
            return Response::make($content, 200, $headers);

        } else {
            echo "Arquivo não encontrado!";
        }
    }

    public function getOferta()
    {
        $this->title = "Ofertas (Agenda)";
        $view = View("admin.{$this->layout}.oferta-agenda.index")->with('title', $this->title);

        return $view;
    }

    public function postOfertaGrid(Requests\Importacao\OfertaRequest $request)
    {
        $view = View("admin.{$this->layout}.oferta-agenda.grid");

        $arenas = LotesArena::select('arenas.*')
            ->join('arenas', 'arenas.id', '=', 'lotes_arena.arena')
            ->where('lote', $request->get('lote'))
            ->where('arenas.ativo', 1)
            ->orderBy('arenas.nome');

        $linha_cuidado = ArenasLinhaCuidado::distinct()->select('linha_cuidado.*')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'arenas_linha_cuidado.linha_cuidado')
            ->whereIn('arena', $arenas->lists('id')->toArray())
            ->where('linha_cuidado.ativo', true)
            ->orderBy('linha_cuidado.nome', 'ASC')
            ->get();

        $view->linha_cuidado = $linha_cuidado;
        $view->arenas = $arenas->get();
        $view->lote = $request->get('lote');
        $view->ano = $request->get('ano');
        $view->mes = $request->get('mes');


        $data = OfertaLoteLinhaCuidado::getByLoteAnoMes($request->get('lote'), $request->get('ano'), $request->get('mes'));
        $_ofertas = [];
        if (!empty($data[0])) {
            foreach ($data AS $row) {
                $_ofertas[$row->arena][$row->linha_cuidado] = $row->qtd;
            }
        }
        $view->ofertas = $_ofertas;

        return $view;
    }

    public function postOfertaSave(Requests\Importacao\OfertaSaveRequest $request)
    {
        $return['status'] = false;

        try {
            OfertaLoteLinhaCuidado::saveData($request->all());
            $return['status'] = true;
        } catch (\Exception $e) {
            $return['message'] = $e->getMessage();
        }

        return $return;
    }

    public function postAgendaDeleteAgendamento(Request $request)
    {
        $id = $request->get('id');

        try {
            $data = Agendas::where('import', 'LIKE', "{$id}-%")->whereIn('status', [0, 1])->delete();
            $return['status'] = true;
        } catch (\Exception $e) {
            $return['message'] = $e->getMessage();
        }

        return $return;
    }

}
