<?php

namespace App\Http\Controllers;

use App\Agendas;
use App\Arenas;
use App\AtendimentoMedicamento;
use App\Atendimentos;
use App\AtendimentoTempo;
use App\Http\Helpers\Util;
use App\LinhaCuidado;
use App\Pacientes;
use App\Http\Requests;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AgendaController extends Controller
{
    public $model = 'Agendas';

    use TraitController;

    public function __construct()
    {
        $this->title = "app.agendas";

        parent::__construct();
    }

    public function getPrintAtendimentoMedico($atendimento)
    {
        $view = View("admin.{$this->layout}.impressao-medicamentos");
        $view->title = "receituario-{$atendimento}";

        $receitas = AtendimentoMedicamento::where('atendimento', $atendimento)
            ->whereIn('medicamento', array(1, 2, 7, 12, 13, 16))
            ->get()
            ->toArray();

        $atendimento = (Object)Atendimentos::get($atendimento);
        $agenda = (Object)Agendas::get($atendimento->agenda);

        $view->agenda = $agenda;
        $view->receitas = !empty($receitas) ? $receitas : null;
        $view->sub_especialidade = null;

        $contents = $view->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($contents, 'UTF-8');
        $dompdf->setPaper('A4');
        $dompdf->render();

        $nome_arquivo = "receituario-" . $atendimento->id;
        $dompdf->stream($nome_arquivo, array("Attachment" => false));
        die;

        return $view;
    }

    public function getGrid()
    {
        return $this->_grid();
    }

    public function postGrid()
    {
        return $this->_grid();
    }

    private function _grid()
    {
        $view = View("admin.{$this->layout}.grid");

        try {
            $sql = $this->objModel->select(
                'agendas.id',
                'agendas.data',
                'agendas.import',
                'pacientes.nome AS paciente_nome',
                'pacientes.nome_social AS paciente_nome_social',
                'pacientes.cns AS paciente_cns',
                'pacientes.cpf AS paciente_cpf',
                'agendas.paciente',
                'agendas.arena',
                'agendas.status',
                'agendas.linha_cuidado',
                'agendas.tipo_atendimento',
                'agendas.ativo',
                'pacientes.nome',
                'arenas.nome AS arenas_nome',
                'arenas.id AS arenas_id',
                'linha_cuidado.nome AS linha_cuidado_nome',
                'linha_cuidado.id AS linha_cuidado_id'
            )
                ->join('arenas', 'arenas.id', '=', 'agendas.arena')
                ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
                ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente');

            $params = Input::all();
            $sql = $this->_paramsSQL($sql, $params);

            if (!empty($params['paciente'])) {
                $sql->orderBy('agendas.data', 'desc');
            } else {
                $sql->orderBy('agendas.data', 'asc');
            }

            $data = $sql->paginate(PAGINATION_PAGES);

            if (empty($data->items())) {
                throw new \Exception("Nenhum registro encontrado!");
            }

            $view->grid = $data;

        } catch (\Exception $e) {
            $view['error'] = $e->getMessage();
        }

        return $view;
    }

    private function _paramsSQL($sql, $params)
    {

        if ($params) {

            if (empty($params['arena'])) {
                throw new \Exception("Selecione uma arena e informe uma parte do nome do paciente para pesquisa");
            }

            if (empty($params['data'])) {
                throw new \Exception("Informe uma data para pesquisa");
            }

            if (empty($params['paciente'])) {
                if ((!empty($params['arena']) && empty($params['data']))) {
                    throw new \Exception("Não é possivel pesquisar uma arena sem data");
                }
            } else {
                if (strlen($params['paciente']) < 4) {
                    throw new \Exception("Informe no minimo 4 letra para pequisar um paciente");
                }
            }

            if (!empty($params['paciente'])) {
                $params['paciente'] = strtoupper(trim($params['paciente']));

                $sql->where(function ($q) use ($params) {
                    $q->where('pacientes.nome', 'LIKE', "%{$params['paciente']}%");
                    $params_int = preg_replace("/[^0-9]/", "", $params['paciente']);

                    if (in_array(strlen($params_int), [10, 11])) {
                        $q->orWhere('pacientes.cpf', $params_int);
                    }

                    if (in_array(strlen($params_int), [15])) {
                        $q->orWhere('pacientes.cns', $params_int);
                    }
                });
            }

            $sql->where('agendas.arena', '=', $params['arena']);

            if (!empty($params['linha_cuidado'])) {
                $sql->where('agendas.linha_cuidado', '=', $params['linha_cuidado']);
            }

            if (!empty($params['data'])) {
                $params['data'] = Util::Date2DB(urldecode($params['data']));

                $sql->whereBetween('agendas.data', array("{$params['data']} 00:00:00", "{$params['data']} 23:59:59"));
            }

            Util::setCookie('agenda-pesquisa-arena', $params['arena']);
        }

        return $sql;
    }

    public function getCancelar($agenda)
    {
        $this->objModel->setStatus($agenda, 0);
    }

    public function getPrint()
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $params = Input::all();

        $sql = $this->objModel->select('agendas.id', 'agendas.data', 'agendas.paciente', 'pacientes.nome AS paciente_nome', 'pacientes.cpf AS paciente_cpf', 'pacientes.nascimento AS paciente_nascimento', 'pacientes.cns AS paciente_cns', 'arenas.nome AS arena', 'agendas.status',
            'linha_cuidado.nome AS linha_cuidado', 'agendas.ativo', 'pacientes.nome')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->orderBy('agendas.data', 'asc');
        //->orderBy('paciente_nome', 'asc');

        $sql = $this->_paramsSQL($sql, $params);
        $data = $sql->get();

        $arena = (Object)Arenas::get($params['arena']);

        $view = View("admin.{$this->layout}.print-atendimentos")->with('title', $arena->nome);
        $view->relatorio = $data;
        $view->arena = $arena;
        $view->linha_cuidado = !empty($params['linha_cuidado']) ? (Object)LinhaCuidado::get($params['linha_cuidado']) : null;
        $view->agendamento = $params['data'];

        $contents = $view->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($contents, 'UTF-8');
        $dompdf->setPaper('A4');
        $dompdf->render();

        $nome_arquivo = "listagem-atendimentos";
        $dompdf->stream($nome_arquivo, array("Attachment" => false));
        die;
    }

    public function getEntry($id = null)
    {
        $view = View("admin.{$this->layout}.entry")->with('title', $this->title);

        $entry = null;
        if ($id) {
            $entry = $this->objModel->find($id);

            $entry->hora = Util::DBTimestamp2UserTime($entry->data);
            $entry->data = Util::DBTimestamp2UserDate($entry->data);

            if (!empty($entry->paciente)) {
                $paciente = Pacientes::get($entry->paciente);

                $entry->paciente = empty($paciente['nome']) ? $entry->paciente : $paciente['cns'] . " - " . $paciente['nome'];
            }
        }
        $view->entry = $entry;

        return $view;
    }

    public function getKitImpressao($agenda, $local = null)
    {
        ini_set('max_execution_time', 90);

        try {
            $view = View("admin.{$this->layout}.kit-impressao")->with('title', "kit-enfermagem-" . $agenda);
            $view->local = $local;

            $agenda = explode("-", $agenda);

            $view->agenda = Agendas::find(current($agenda));
            $view->sub_especialidade = !empty($agenda[1]) ? $agenda[1] : null;

            $atendimento = Atendimentos::ByAgenda(current($agenda));
            AtendimentoTempo::recepcaoOut($atendimento->id);

            $contents = $view->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($contents, 'UTF-8');
            $dompdf->setPaper('A4');
            $dompdf->render();

            $dompdf->get_canvas()->page_text(532, 818, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 6, array(0, 0, 0));

            $nome_arquivo = "kit-impressao-" . $view->agenda->id;
            $dompdf->stream($nome_arquivo, array("Attachment" => false));

            die;
        } catch (\Exception $e) {
            $view->messagem_error = $e->getMessage();
        }

        return $view;
    }

    public function getApacImpressao($agenda)
    {
        $view = View("admin.{$this->layout}.apac-impressao")->with('title', $agenda);
        $view->agenda = Agendas::find($agenda);

        $contents = $view->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($contents, 'UTF-8');
        $dompdf->setPaper('A4');
        $dompdf->render();

        $nome_arquivo = "apac-" . $view->agenda->id;
        $dompdf->stream($nome_arquivo, array("Attachment" => false));
        die;
    }

    public function postFalta()
    {
        $params = Input::all();

        $this->objModel->setStatus($params['id'], 7);

        return 1;
    }

    public function postIndex(Requests\AgendasRequest $request)
    {
        $data = $request->all();

        if (!empty($data['paciente'])) {
            $cns = trim(current(explode(" - ", $data['paciente'])));

            $paciente = Pacientes::getByCNS($cns);

            if (empty($paciente)) {
                $data['paciente'] = null;

                $rules['paciente'] = 'required';
                $validator = Validator::make($request->all(), $rules);

                $validator->errors()->add('paciente', 'Digite o nome do paciente e selecione na listagem exibida');

                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data['paciente'] = $paciente->id;
            }
        }

        $save = $this->objModel->saveData($data);

        return redirect("admin/{$this->layout}/list");
    }

    public function postDadosComplementar()
    {
        $params = Input::all();

        $data = [];
        if ($params['agenda']) {

            $atendimentos = Atendimentos::select(
                [
                    'atendimento.id',
                    'atendimento.agenda',
                    'agendas.status AS agenda_status',
                    'atendimento.status AS atendimento_status',
                    'atendimento.etapa AS atendimento_etapa',
                ]
            )
                ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
                ->whereIn('atendimento.agenda', $params['agenda'])
                ->whereIn('agendas.id', $params['agenda'])
                ->get();

            foreach ($atendimentos as $row) {
                $biospia = \App\AtendimentoLaudo::getBiopsia($row->id);

                $data[$row->agenda]['agenda'] = $row->agenda;
                $data[$row->agenda]['id'] = $row->id;
                $data[$row->agenda]['laudo'] = (count($biospia)) ? 1 : 0;
                $data[$row->agenda]['agenda_status'] = $row->agenda_status;
                $data[$row->agenda]['atendimento_status'] = $row->atendimento_status;
                $data[$row->agenda]['html_agenda_status'] = \App\Http\Helpers\Util::StatusAgenda($row->agenda_status);
                $data[$row->agenda]['html_atendimento_status'] = "<span class='label bg-primary pos-rlt m-r-xs'>" . \App\Http\Helpers\Util::ValidaEtapas($row->atendimento_etapa) . "</span>";
                $data[$row->agenda]['html_biopsia'] = (count($biospia)) ? "<i class='btn-resultado-biopsia mdi-maps-local-hospital text-lg m-t-sm pull-left' rel='{$row->id}' style='color: #F00; margin-top: -1px'></i>" : null;
            }
        }

        return json_encode($data);
    }

    public function postRemarcar()
    {
        $params = Input::all();

        $view = View('admin.agendas.remarcar');

        $agenda = Agendas::find($params['agenda']);
        $date = explode(" ", $agenda->data);

        $view->agenda = $agenda;
        $view->time = substr($date[1], 0, 5);

        return $view;
    }

    public function postRemarcacao()
    {
        $params = Input::all();

        DB::transaction(function () use ($params) {
            $return = [];
            try {
                $motivo_remarcacao = $params['motivo_remarcacao'];
                $data = $params['data'];
                $hora = $params['hora'];

                if (intval($motivo_remarcacao) == 0) {
                    throw new \Exception("Informe um motivo de remarcação!");
                }

                if (empty($data)) {
                    throw new \Exception("Data inválida!");
                }

                $data = Util::Date2DB($data);

                if($data <= date('Y-m-d')){
                    throw new \Exception("A data deve ser superior ao dia de hoje!");
                }

                if (empty($hora) && strlen($hora) != 5) {
                    throw new \Exception("Hora inválida ou não existente!");
                }

                $date = $data . " " . $hora . ":00";

                $agenda = Agendas::find($params['agenda']);
                $agenda->status = 3;
                $agenda->motivo_remarcacao = $motivo_remarcacao;
                $agenda->save();

                $new_agenda = $agenda->replicate();
                $new_agenda->agenda = $params['agenda'];
                $new_agenda->data = $date;
                $new_agenda->import = null;
                $new_agenda->motivo_remarcacao = null;
                $new_agenda->status = 1;

                if (empty($new_agenda->procedimento)) {
                    unset($new_agenda->procedimento);
                }

                if (empty($new_agenda->arena_equipamento)) {
                    unset($new_agenda->arena_equipamento);
                }

                if (empty($new_agenda->medico)) {
                    unset($new_agenda->medico);
                }

                $new_agenda->save();

                DB::commit();

                $return['success'] = true;
            } catch (\Exception $e) {
                $return['success'] = false;
                $return['message'] = $e->getMessage();

                DB::rollBack();
            }

            return exit(json_encode($return));
        });
    }

    public function postDescancelar()
    {
        $params = Input::all();

        DB::transaction(function () use ($params) {
            $return = [];
            try {

                Agendas::where('id', $params['agenda'])
                    ->update([
                        'status' => 1
                    ]);

                DB::commit();

                $return['success'] = true;
            } catch (\Exception $e) {
                $return['success'] = false;
                $return['message'] = $e->getMessage();

                DB::rollBack();
            }

            return exit(json_encode($return));
        });
    }

}
