<?php

namespace App\Http\Controllers;

use App\Agendas;
use App\AnamnesePerguntas;
use App\AtendimentoArquivos;
use App\AtendimentoAuxiliar;
use App\AtendimentoCheckList;
use App\AtendimentoLaudo;
use App\AtendimentoLaudoImagens;
use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\AtendimentoStatus;
use App\AtendimentoTempo;
use App\Http\Helpers\AtendimentoHelpers;
use App\Http\Helpers\Upload;
use App\Http\Helpers\UsuarioHelpers;
use App\Http\Helpers\Util;
use App\Http\Requests\AtendimentoLaudoUploadRequest;
use App\LaudoMedico;
use App\LinhaCuidado;
use App\Pacientes;
use App\TipoAtendimento;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Zend\Filter\Digits;

class AtendimentoController extends Controller
{
    public $model = 'Atendimentos';

    public function __construct()
    {
        parent::__construct();
    }

    public function getConduta($agenda)
    {
        $view = View("admin.atendimentos.conduta.index");

        $agenda = Agendas::get($agenda);
        $atendimento = Atendimentos::ByAgenda($agenda['id']);

        $view->atendimento = $atendimento;
        $view->tipo_atendimento = $atendimento->tipo_atendimento;
        $view->tipos_atendimento = TipoAtendimento::Combo();
        $view->condutas = AtendimentoHelpers::getCondutasEspecialidadeTipoAtendimento($agenda['linha_cuidado'], $atendimento->tipo_atendimento);
        $view->condutas_regulacao = AtendimentoHelpers::getCondutasEspecialidadeTipoAtendimento($agenda['linha_cuidado'], $atendimento->tipo_atendimento, 1);
        $view->lateralidades = AtendimentoHelpers::getLateralidades();

        $view->entry = AtendimentoAuxiliar::getByAtendimento($atendimento->id);
        if (is_object($view->entry)) {
            $view->entry->tipo_atendimento = $atendimento->tipo_atendimento;
        }

        return $view;
    }

    public function postConduta(Requests\Admin\Atendimento\AtendimentoCondutaRequest $request)
    {
        $response['status'] = false;

        try {
            $atendimento_auxiliar = AtendimentoAuxiliar::getByAtendimento($request->get('atendimento'));
            if (is_null($atendimento_auxiliar)) {
                $atendimento_auxiliar = new AtendimentoAuxiliar();
                $atendimento_auxiliar->atendimento = $request->get('atendimento');
            }
            $atendimento_auxiliar->conduta = $request->get('conduta');
            $atendimento_auxiliar->conduta_secundaria = $request->get('conduta_secundaria');
            $atendimento_auxiliar->conduta_descricao = $request->get('conduta_descricao');
            $atendimento_auxiliar->conduta_regulacao = $request->get('conduta_regulacao');
            $atendimento_auxiliar->conduta_opcao = $request->get('conduta_opcao');
            $atendimento_auxiliar->save();

            $atendimento = Atendimentos::find($request->get('atendimento'));
            $atendimento->tipo_atendimento = $request->get('tipo_atendimento');
            $atendimento->save();

            $response['status'] = true;
            $response['url'] = 'function';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function postAnexos(Requests\Admin\Atendimento\AtendimentoAnexosRequest $request)
    {
        $response['status'] = false;

        try {
            $atendimento = Atendimentos::get($request->get('atendimento'));

            $atendimento_arquivos = new AtendimentoArquivos();
            $atendimento_arquivos->atendimento = $atendimento['id'];
            $atendimento_arquivos->user = Util::getUser();
            $atendimento_arquivos->tipo = $request->get('tipo');
            $atendimento_arquivos->anotacao = $request->get('anotacao');
            $atendimento_arquivos->arquivo = Upload::uploadAtendimentoArquivos($request->file('arquivo'), $request->get('atendimento'), $request->get('tipo'), $atendimento['created_at']);;
            $atendimento_arquivos->save();

            $response['status'] = true;
            $response['url'] = 'function';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function getAnexos($agenda)
    {
        $view = View("admin.atendimentos.anexos.index");

        $atendimento = Atendimentos::ByAgenda($agenda);

        $view->atendimento = $atendimento;
        $view->entry = null;

        return $view;
    }

    public function postAnexosListagem($atendimento)
    {
        $view = View("admin.atendimentos.anexos.listagem");

        $view->atendimento = Atendimentos::get($atendimento);

        if (\App\Http\Helpers\UsuarioHelpers::isNivelCirurgico()) {
            $view->anexos = Atendimentos::getAnexos($atendimento);
        } else {
            $paciente = Atendimentos::getPacienteByAtendimento($atendimento);
            $view->anexos = Atendimentos::getAnexosHistorico($paciente->id);
        }

        return $view;
    }

    public function postAnexosDelete(Requests\AtendimentoRecepcaoResquest $request)
    {
        $id = $request->get('id');

        try {
            $_atendimento_arquivo = new AtendimentoArquivos();
            $_atendimento_arquivo = $_atendimento_arquivo->find($id);
            if (!empty($_atendimento_arquivo->id)) {
                $_atendimento_arquivo->status = 0;
                $_atendimento_arquivo->save();
            }

            return ['status' => true];
        } catch (\Exception $e) {

            return ['status' => false];
        }
    }

    public function postSaveRecepcao(Requests\AtendimentoRecepcaoResquest $request)
    {
        $data = $request->all();

        exit(json_encode(array('status' => Atendimentos::saveRecepcao($data))));
    }

    public function postSaveProcedimento(Requests\AtendimentoProcedimentosRequest $request)
    {
        $data = $request->all();

        exit(json_encode(array('status' => Atendimentos::saveProcedimento($data))));
    }

    public function postSaveMedicamento(Requests\AtendimentoMedicamentoRequest $request)
    {
        $data = $request->all();

        exit(json_encode(array('status' => Atendimentos::saveMedicamentos($data))));
    }

    public function postSaveEvolucao(Requests\AtendimentoMedicamentoRequest $request)
    {
        $data = $request->all();

        $req['id'] = $data['atendimento'];
        $req['evolucao'] = $data['value'];

        exit(json_encode(array('status' => Atendimentos::saveEvolucao($req))));
    }

    public function postSaveAnotacao(Requests\AtendimentoMedicamentoRequest $request)
    {
        $data = $request->all();

        $req['id'] = $data['atendimento'];
        $req['anotacao'] = $data['value'];

        exit(json_encode(array('status' => Atendimentos::saveAnotacao($req))));
    }

    public function postSaveLaudo(Request $request)
    {
        try {
            $data = $request->all();

            AtendimentoTempo::medicinaOUT($data['atendimento']);

            $atendimento_laudo = Atendimentos::saveLaudo($data);

            if (!empty($atendimento_laudo->id)) {
                $this->generateLaudo($atendimento_laudo, true);
            }

            exit(json_encode(array('status' => (!is_object($atendimento_laudo) && $atendimento_laudo == 2) ? true : $atendimento_laudo->toArray())));

        } catch (\Exception $e) {
            echo $e->getLine();
            exit("<pre>LINE: " . __LINE__ . " - " . print_r($e->getMessage(), 1) . "</pre>"); #debug-edersonsandre
        }
    }

    public function urlsafe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public function getPrintLaudo($id)
    {
        $atendimento_laudo = AtendimentoLaudo::find($id);

        try {

            if (empty($atendimento_laudo->id)) {
                throw new \Exception("Não foi possivel encontrar o laudo!");
            }

            $pdf = $this->generateLaudo($atendimento_laudo);

            $nome_arquivo = sha1("laudo-" . $atendimento_laudo->id);
            $pdf->stream($nome_arquivo, array("Attachment" => false));

        } catch (\Exception $e) {
            exit("<pre>" . print_r($e->getMessage(), true) . "</pre>");
            $view = View("admin.atendimentos.print-laudo");
            $view->laudo = null;

            return $view;
        }
    }

    public function generateLaudo($laudo, $storage = false)
    {
        $view = View("admin.atendimentos.print-laudo");

        $atendimento = (object)Atendimentos::get($laudo->atendimento);
        $agenda = (object)Agendas::get($atendimento->agenda);

        $view->laudo = $laudo;
        $view->atendimento = $atendimento;
        $view->agenda = $agenda;
        $view->title = $atendimento->id;
        $view->imagens = null;// AtendimentoLaudoImagens::getByAtendimentoLaudo($laudo->id);

        $data = [
            'atendimento' => $atendimento->id,
            'laudo' => $laudo->id,
            'agenda' => $atendimento->agenda,
        ];

        $qrCode = new QrCode(json_encode($data, 1));

        $qrCode
            ->setWriterByName('png')
            ->setMargin(10)
            ->setEncoding('UTF-8')
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
            ->setValidateResult(false);

        $view->qrcode = $qrCode->writeDataUri();

        $contents = $view->render();

        $pdf = new Dompdf();
        $pdf->loadHtml($contents);
        $pdf->setPaper('A4');
        $pdf->render();

        if ($storage) {
            try {
                Upload::uploadLaudoAWS($laudo, $pdf->output());
            } catch (\Exception $e) {

            }
        }

        return $pdf;
    }

    public function postCheckList(
        Requests\AtendimentoResquest $request
    )
    {
        $return['status'] = false;

        $data = $request->all();

        switch ($data['campo']) {
            case 'nome':
            case 'nome_social':
            case 'mae':
            case 'nascimento':
            case 'cep':
            case 'complemento':
            case 'cns':
            case 'numero':
            case 'bairro':
            case 'endereco':
            case 'cidade':
            case 'raca_cor':
            case 'nacionalidade':
            case 'sexo':
            case 'endereco_tipo':
            case 'celular':
            case 'rg':
            case 'cpf':
            case 'telefone_residencial':
                $data['value'] = Util::String2DB($data['value']);

                if ($data['campo'] == 'nascimento') {
                    $data['value'] = Util::Date2DB($data['value']);
                }

                $agenda = Agendas::find($data['agenda']);

                $digits = new Digits();
                if (in_array($data['campo'], array('celular', 'cep', 'cpf'))) {
                    $data['value'] = $digits->filter($data['value']);
                }

                DB::table('pacientes')
                    ->where('id', $agenda->paciente)
                    ->update([$data['campo'] => $data['value']]);

                $return['status'] = true;
                break;
            case  'sala':
            case  'procedimento':
            case  'preferencial':
            case  'transporte':
                DB::table('atendimento')
                    ->where('id', $data['atendimento'])
                    ->update([$data['campo'] => $data['value']]);

                AtendimentoCheckList::checkPreferencial($data['atendimento']);

                $return['status'] = true;
                break;
            case 'documento_foto' :
            case 'cartao_nacional_saude' :
            case 'pedido_medico' :
            case 'comprovante_agendamento' :

                $check = AtendimentoCheckList::where('atendimento', $data['atendimento'])->first();

                if (!$check) {
                    $check = new AtendimentoCheckList();
                    $check->atendimento = $data['atendimento'];
                    $check_id = $check->save();
                } else {
                    $check_id = $check->id;
                }

                DB::table('atendimento_check_list')
                    ->where('id', $check_id)
                    ->update([$data['campo'] => $data['value']]);

                $return['status'] = true;
                break;
        }

        exit(json_encode($return));
    }

    public function getCheckList(
        $agenda
    )
    {
        $view = View("admin.{$this->layout}.check-list");

        $agenda = Agendas::find($agenda);

        $paciente = Pacientes::find($agenda->paciente);
        if ($paciente) {
            $paciente->nascimento = Util::DB2User($paciente->nascimento);
        }

        $atendimento = Atendimentos::ByAgenda($agenda->id);
        AtendimentoTempo::recepcaoIN($atendimento->id);

        $paciente->atendimento = $atendimento->id;
        $paciente->agenda = $agenda->id;

        if ($atendimento) {
            $paciente->sala = $atendimento->sala;
            $paciente->preferencial = $atendimento->preferencial;
            //$paciente->transporte = $atendimento->transporte;
            $paciente->linha_cuidado = $agenda->linha_cuidado;
        }

        $view->linhas_cuidado = LinhaCuidado::ByArena($agenda->arena);

        $check = AtendimentoCheckList::where('atendimento', $atendimento->id)->first();
        if ($check) {
            $paciente->documento_foto = $check->documento_foto;
            $paciente->cartao_nacional_saude = $check->cartao_nacional_saude;
            $paciente->pedido_medico = $check->pedido_medico;
            $paciente->comprovante_agendamento = $check->comprovante_agendamento;
        }

        $view->questionario = AnamnesePerguntas::Questionario(5);
        $view->entry = $paciente;
        return $view;
    }

    public function getView($agenda)
    {
        $view = View("admin.{$this->layout}.view");
        try {
            $view->questionario = AnamnesePerguntas::Questionario(1);

            $atendimento = Atendimentos::ByAgenda($agenda);
            $view->atendimento = $atendimento;
            $view->agenda = Agendas::find($agenda);
            $view->linha_cuidado = LinhaCuidado::find($view->agenda->linha_cuidado);

            AtendimentoTempo::medicinaIN($atendimento->id);
        } catch (\Exception $e) {
            $view->error = $e->getMessage();
        }

        return $view;
    }

    public function postProgress()
    {
        $data = Input::all();

        $id = $data['atendimento'];
        $atendimento = Atendimentos::find($id);

        switch ($atendimento->etapa) {
            case 0 :
                $progreso = 10;
                break;
            case 1 :
                $progreso = 40;
                break;
            case 2 :
                $progreso = 60;
                break;
            case 3 :
                $progreso = 80;
                break;
            case 4 :
                $progreso = 100;
                break;
        }

        return "<div style='width:{$progreso}%' class='progress-bar progress-bar-danger'>{$progreso} %</div>";
    }

    public function getGridLaudo(
        $atendimento
    )
    {
        $view = View("admin.{$this->layout}.grid-laudo");


        $laudos = Atendimentos::laudoImpressao($atendimento);

        $view->grid = $laudos;
        $view->atendimento = Atendimentos::get($atendimento);

        return $view;
    }

    public function getLaudoBiopsia(
        $atendimento
    )
    {
        $view = View("admin.{$this->layout}.laudo-biopsia");

        $view->laudos = AtendimentoLaudo::getBiopsia($atendimento);

        return $view;
    }

    public function postLaudo()
    {
        $data = Input::all();

        try {

            if (!empty($data['laudo'])) {
                $laudo = AtendimentoLaudo::find($data['laudo']);
                if (!empty($laudo)) {
                    $laudo->status_biopsia = $data['status_biopsia'];
                    $laudo->resultado_biopsia = $data['resultado_biopsia'];
                    $laudo->save();
                }
            }

        } catch (\Exception $e) {
            echo $e->getLine();
            exit("<pre>LINE: " . __LINE__ . " - " . print_r($e->getMessage(), 1) . "</pre>"); #debug-edersonsandre
        }

    }

    public function getDeleteLaudo(
        $laudo
    )
    {
        Atendimentos::deleteLaudo($laudo);
    }

    public function getCheckInMedicina(
        $atendimento
    )
    {
        // AtendimentoTempo::medicinaIN($atendimento);
    }

    public function postFaturamentoValidaAtendimento(){
        $data = Input::all();
        $return['success'] = true;

        if(!AtendimentoHelpers::validaProcedimentoAPACByAgenda($data['agenda']) && !in_array(Auth::user()->level, [19])){
            $return['success'] = false;
            $return['message'] = "Agendamento {$data['agenda']}, contém procedimento(s) com obrigatoriedade de preenchimento do numero da APAC.";
        }

        return json_encode($return);
    }

    public function postAtendimentoMedicoMassa()
    {
        $data = Input::all();
        $return = [];
        $return['message'] = "Nenhum atendimento foi encontrado para atualização.";

        $data_final = Carbon::now()->subDay()->toDateString();

        $linha_cuidado = $data['linha_cuidado'];
        $arena = $data['arena'];
        $procedimento = $data['procedimento'];

        if ($data['quantidade'] > 0) {
            $sql = Agendas::select(
                [
                    'agendas.id AS agendas_id',
                    'atendimento.id',
                ]
            )
                ->where('agendas.arena', $arena)
                ->where('agendas.linha_cuidado', $linha_cuidado)
                ->where('agendas.data', "<=", $data_final . " 23:59:59")
                ->whereIn('agendas.id', $data['agendas'])
                ->whereIn('agendas.status', [10])
                ->join('atendimento', function ($join) {
                    $join->on('agendas.id', '=', 'atendimento.agenda')
                        ->whereNotIn('atendimento.status', [6, 98, 99]);
                })
                ->orderBy('agendas.id', 'asc')
                ->limit($data['quantidade']);

            if ($procedimento) {
                $sql->where('agendas.procedimento', $procedimento);
            }

            $atendimentos = $sql->get();

            $ids = [];
            foreach ($atendimentos as $atendimento) {
                $agendas_id = $atendimento->agendas_id;

                $ids[] = $atendimento->id;
                $data['atendimentos'][] = $agendas_id;
            }
        } else {
            $ids = [];
            foreach ($data['atendimentos'] as $agenda) {
                $atendimento = Atendimentos::ByAgenda($agenda);

                $ids[] = $atendimento->id;
            }
        }

        if (count($ids)) {
            $status = 6;
            if (UsuarioHelpers::isNivelCirurgico()) {
                $status = 8;
            }

            Atendimentos::whereIn('id', $ids)
                ->update(
                    [
                        'medico' => $data['medico'],
                        'status' => $status
                    ]
                );

            foreach ($ids as $id) {
                AtendimentoStatus::setStatus($id, $status);
            }

            AtendimentoProcedimentos::whereIn('atendimento', $ids)
                ->update(
                    [
                        'profissional' => $data['medico'],
                        'faturista' => Util::getUser(),
                        'finalizacao' => date('Y-m-d H:i:s')
                    ]
                );

            if (count($data['atendimentos'])) {
                Agendas::whereIn('id', $data['atendimentos'])
                    ->update(['status' => $status]);
            }

            $return['message'] = Util::getUserName() . " foram atualizados " . count($ids) . " atendimentos";
        }

        return json_encode($return);
    }

    public function postAtendimentoMedicoDadosComplementar()
    {
        $data = Input::all();
        $_data = [];

        $agendas = $data['agenda'];

        $atendimentos = Agendas::select(
            [
                'agendas.id as agendas_id',
                'atendimento.id as atendimento_id',
                'atendimento_procedimentos.id as atendimento_procedimentos_id',
                'atendimento_procedimentos.procedimento as atendimento_procedimentos_procedimento',
                'profissionais.nome as profissionais_nome',
                'profissionais.cns as profissionais_cns',
                'profissionais.cro as profissionais_cro',
                'profissionais.cpf as profissionais_cpf',
                'profissionais.ativo as profissionais_ativo',
                'procedimentos.id as procedimentos_id',
                'procedimentos.nome as procedimentos_nome',
                'atendimento_procedimentos.quantidade as atendimento_procedimentos_quantidade',
                'faturista.name as faturista',
            ]
        )
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->leftJoin('profissionais', 'profissionais.id', '=', 'atendimento.medico')
            ->leftJoin('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->leftJoin('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->leftJoin('users AS faturista', 'faturista.id', '=', 'atendimento_procedimentos.faturista')
            ->whereIn('agendas.id', $agendas)
            ->get();

        foreach ($atendimentos as $atendimento) {
            $agenda = $atendimento->agendas_id;

            $_data[$agenda]['medico']['nome'] = $atendimento->profissionais_nome;
            $_data[$agenda]['medico']['cns'] = $atendimento->profissionais_cns;
            $_data[$agenda]['medico']['cro'] = $atendimento->profissionais_cro;
            $_data[$agenda]['medico']['cpf'] = $atendimento->profissionais_cpf;
            $_data[$agenda]['medico']['ativo'] = $atendimento->profissionais_ativo;

            $_data[$agenda]['procedimentos'][] = [
                'id' => $atendimento->procedimentos_id,
                'nome' => $atendimento->procedimentos_nome,
                'quantidade' => $atendimento->atendimento_procedimentos_quantidade,
                'faturista' => $atendimento->faturista
            ];
        }

        return json_encode($_data);
    }

    public function postAtendimentoMedicoDadosComplementarAnexos()
    {
        $data = Input::all();
        $_data = [];

        $agendas = $data['agenda'];

        $atendimentos = Agendas::select(
            [
                'agendas.id as agendas_id',
                'atendimento.id as atendimento_id',
                DB::raw('COUNT(atendimento_arquivos.id) as arquivos'),
                DB::raw('COUNT(DISTINCT atendimento_auxiliar.id) as condutas'),
            ]
        )
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->leftJoin('atendimento_arquivos', function ($join) {
                $join->on('atendimento.id', '=', 'atendimento_arquivos.atendimento')
                    ->where('atendimento_arquivos.status', '=', 1);
            })
            ->leftJoin('atendimento_auxiliar', function ($join) {
                $join->on('atendimento.id', '=', 'atendimento_auxiliar.atendimento')
                    ->whereNotNull('atendimento_auxiliar.conduta');
            })
            ->whereIn('agendas.id', $agendas)
            ->groupBy(['agendas.id', 'atendimento.id'])
            ->get();

        foreach ($atendimentos as $atendimento) {
            $agenda = $atendimento->agendas_id;

            $_data[$agenda]['arquivos'] = $atendimento->arquivos;
            $_data[$agenda]['condutas'] = $atendimento->condutas;
        }

        return json_encode($_data);
    }

    public function postAtendimentoMedicoProcedimento()
    {
        $view = View("admin.{$this->layout}.atendimento-medico-procedimento");
        $data = Input::all();

        $view->atendimento = $atendimento = Atendimentos::ByAgenda($data['agenda']);
        $view->procedimentos = AtendimentoProcedimentos::getProcedimentosFullByLinhaCuidadoAtendimento($atendimento);

        $view->linha_cuidado = LinhaCuidado::get($atendimento->linha_cuidado);

        return $view;
    }

    public function postAtendimentoMedicoProcedimentoMassa()
    {
        $data = Input::all();
        $atendimento = $data['atendimento'];
        $procedimentos = array_values($data['_procedimentos']);

        if (!$data['checked']) {
            $_procedimentos = AtendimentoProcedimentos::where('atendimento', $atendimento)->whereIn('procedimento', $procedimentos)->delete();
        } else {

            foreach ($data['_procedimentos'] as $procedimento) {
                $atendimento_procedimento_id = Atendimentos::getByIDAtendimentoProcedimento($atendimento, $procedimento);
                $atendimento_procedimento = !empty($atendimento_procedimento_id) ? AtendimentoProcedimentos::find($atendimento_procedimento_id) : new AtendimentoProcedimentos();

                if (empty($atendimento_procedimento->id)) {
                    $atendimento_procedimento->procedimento = $procedimento;
                    $atendimento_procedimento->atendimento = $atendimento;
                    $atendimento_procedimento->autorizacao = null;
                    $atendimento_procedimento->quantidade = 1;
                    $atendimento_procedimento->user = Util::getUser();
                    $atendimento_procedimento->save();
                }
            }
        }

        return ['success' => true];
    }

    public function postAtendimentoMedicoProcedimentoSave()
    {
        $data = Input::all();

        $atendimento_procedimento_id = Atendimentos::getByIDAtendimentoProcedimento($data['atendimento'], $data['procedimento']);
        $atendimento_procedimento = !empty($atendimento_procedimento_id) ? AtendimentoProcedimentos::find($atendimento_procedimento_id) : new AtendimentoProcedimentos();

        if ($data['checked']) {
            $digits = new Digits();
            $data['autorizacao'] = $digits->filter($data['autorizacao']);

            $atendimento_procedimento->procedimento = $data['procedimento'];
            $atendimento_procedimento->atendimento = $data['atendimento'];
            $atendimento_procedimento->autorizacao = (intval($data['autorizacao']) == 0) ? null : $data['autorizacao'];
            $atendimento_procedimento->quantidade = ($data['quantidade'] == 0) ? 1 : $data['quantidade'];
            $atendimento_procedimento->user = Util::getUser();
            $atendimento_procedimento->save();
        }

        if (!$data['checked'] && count($atendimento_procedimento)) {
            $atendimento_procedimento->delete();
        }

        return ['success' => true];
    }

    public function postFinalizar()
    {
        $data = Input::all();

        try {
            Atendimentos::finalizar($data['atendimento']);

            return ['status' => true];
        } catch (\Exception $e) {

            return ['status' => false];
        }
    }


    public function postFinalizarDigitador()
    {
        $data = Input::all();

        try {
            $linha_cuidado = Atendimentos::getLinhaCuidado($data['atendimento']);

            if ($linha_cuidado->especialidade == 1) {
                $laudos = Atendimentos::getLaudoByAtendimento($data['atendimento']);
                if ($laudos == null) {
                    throw new \Exception("Não é possivel finalizar o atendimento sem laudo!");
                }
            }

            Atendimentos::finalizarDigitador($data['atendimento']);

            return ['status' => true];
        } catch (\Exception $e) {

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function postResetAgenda()
    {
        $return['success'] = false;

        try {
            $data = Input::all();

            Atendimentos::CancelaAtendimento($data['agenda']);

            DB::commit();
            $return['success'] = true;
        } catch (\Exception $e) {
            DB::rollBack();

            $return['message'] = $e->getMessage();
            $return['success'] = false;

        }

        return json_encode($return);
    }

    public function postMedico(
        Request $request
    )
    {
        $medico = $request->get('medico');
        $atendimento = $request->get('atendimento');

        $return['status'] = false;

        if (!empty($medico)) {
            try {
                Atendimentos::_updateProfissionalAtendimento($atendimento, $medico);
                $return['status'] = true;
            } catch (\Exception $e) {

            }
        }
        return $return;
    }

    public function getSearch(
        $atendimento = null
    )
    {
        if (strlen($atendimento) > 4) {
            $atendimentos = Atendimentos::SearchByAtendimento($atendimento, 5);
        }

        return json_encode(!empty($atendimentos) ? $atendimentos : array());
    }

    /**
     * @param $laudo
     * @return string
     */
    public function getLaudoDescricao(
        $laudo
    )
    {
        $descricao = urldecode(LaudoMedico::getLaudoDescricao($laudo));

        $descricao = preg_replace('/( )+/', ' ', $descricao);
        $descricao = preg_replace('/[\n\r\t]/', ' ', $descricao);
        $descricao = str_replace("<p>&nbsp;</p>", "<br />", $descricao);
        $descricao = str_replace("<br />    <br />", "<br />", $descricao);
        $descricao = str_replace("<br />    <p>", "<p>", $descricao);

        return $descricao;
    }

    public function postAtendimentoLaudoUploadImagem(
        AtendimentoLaudoUploadRequest $request
    )
    {
        Upload::laudoAtendimentoImagens($request->get('atendimento_laudo'), $request->file('file'));
    }

    public function getLaudoImagens(
        $atendimento
    )
    {
        $view = View("admin.{$this->layout}.laudo-imagens");

        $view->atendimento = Atendimentos::get($atendimento);
        $view->arquivos = AtendimentoLaudoImagens::getImagensGrupo($atendimento);

        return $view;
    }

    public function getAtendimentoLaudoImagem(
        $id
    )
    {
        AtendimentoLaudoImagens::where('id', $id)->update(['ativo' => false]);

        return ['success' => true];
    }

}
