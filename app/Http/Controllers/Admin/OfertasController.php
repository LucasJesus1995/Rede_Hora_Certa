<?php

namespace App\Http\Controllers\Admin;

use App\ArenaEquipamentos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitController;
use App\Http\Helpers\Util;
use App\Http\Requests\Admin\OfertaAprovacaoRequest;
use App\Http\Requests\Admin\OfertaOcorrenciasRequest;
use App\Http\Requests\Admin\OfertaPesquisaRequest;
use App\Http\Requests\Admin\OfertaRelatorioEscalaRequest;
use App\Http\Requests\Admin\OfertaRelatorioRequest;
use App\Http\Requests\Admin\OfertasRequest;
use App\Http\Requests\Admin\OfertaStatusRequest;
use App\Http\Requests\Importacao\OfertaImportacaExcelRequest;
use App\Http\Rules\Ofertas\ImportacaoExcelRules;
use App\LinhaCuidado;
use App\OfertaOcorrencias;
use App\Ofertas;
use App\Procedimentos;
use App\Profissionais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Style_Alignment;

class OfertasController extends Controller
{
    public $model = 'Ofertas';

    use TraitController;

    public function __construct()
    {
        $this->title = "Ofertas";

        parent::__construct();
    }

    public function getImportacaoExcel()
    {
        $view = View("admin.{$this->layout}.importacao-excel.index");
        $view->entry = null;

        return $view;
    }

    public function postImportacaoExcel(OfertaImportacaExcelRequest $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 600);

        $exception = null;
        $file = $request->file('file');

        Excel::load($file, function ($sheet) use (&$exception) {
            $imports = new ImportacaoExcelRules();
            $ln = 2;

            $sheet->each(function ($row) use ($imports, &$ln, &$exception) {
                try {
                    $oferta = $imports->clearRow($row->toArray());
                    if (is_array($oferta)) {
                        Ofertas::saveOfertas($oferta);
                    }else{
                        $linha = str_pad($ln, 4, 0, STR_PAD_LEFT);
                        $exception[$ln][] = "Não foi possivel importar";
                    }
                } catch (\Exception $e) {
//                    print("<pre>" . print_r($oferta, 1) . "</pre>");
//                    print("<pre>" . print_r($row->toArray(), 1) . "</pre>");
//                    exit("<pre>" . print_r($e->getMessage(), 1) . "</pre>");
                    $linha = str_pad($ln, 4, 0, STR_PAD_LEFT);
                    $exception[$ln][] = $e->getMessage();
                }

                $ln++;
            });

        });

        if (is_array($exception)) {
            $error = null;
            foreach ($exception as $k => $rows) {
                foreach ($rows as $row) {
                    $error .= "<strong>ERROR: Linha {$k}</strong> > {$row}<br />";
                }
            }
            return redirect('/admin/ofertas/importacao-excel')->with('error', $error);
        } else {
            return redirect('/admin/ofertas/importacao-excel')->with('status', "Importação efetuada com sucesso!");
        }
    }


    public function getRelatorioEscala()
    {
        $view = View("admin.{$this->layout}.relatorio-escala.index");
        $view->entry = null;

        return $view;
    }

    public function postRelatorioEscala(OfertaRelatorioEscalaRequest $request)
    {
        $view = View("admin.{$this->layout}.relatorio-escala.data");

        try {
            $data = Ofertas::getEscala($request->get('mes'), $request->get('unidade'));

            if (!empty($data[0])) {
                $headers = ['DIA', 'ESPECIALIDADE', 'UNIDADE', 'MÊS', 'SEMANA', 'MÉDICO', 'STATUS', 'EQUIPAMENTO', 'CLASSIFICAÇÃO', 'PERIODO', 'H. INICIAL', 'H. FINAL', 'QUANTIDADE', 'NATUREZA'];
                $lines[] = implode(";", $headers);
                foreach ($data->toArray() as $row) {
                    $lines[] = implode(";", $row);
                }

                $path = PATH_FILE_RELATORIO . 'excel/ofertas/' . Util::getUser() . '/';
                $filename = "ofertas-escala.csv";
                file_put_contents(public_path($path . $filename), implode("\r\n", $lines));

                $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;
            }
        } catch (\Exception $e) {
//            exit("<pre>" . print_r($e->getMessage(), true) . "</pre>");
            $view->link = null;
        }

        return $view;
    }


    public function postDeleteMassa(Request $request)
    {
        $response['status'] = false;

        $sql = Ofertas::select(
            [
                'ofertas.*',
            ]
        );

        $data_pesquisa = $request->get('pesquisa');
        if (!empty($data_pesquisa['repetir_semana'])) {
            $data_pesquisa['repetir_semana'] = explode(",", $data_pesquisa['repetir_semana']);
        }

        $sql = $this->getSearchGrid($sql, $data_pesquisa)->get();

        if (!empty($sql[0])) {
            $ofertas = array_column($sql->toArray(), 'id');

            DB::transaction(function () use ($ofertas, $request, &$response) {

                try {
                    Ofertas::whereIn('id', $ofertas)->delete();
                    DB::commit();

                    $response['status'] = true;
                } catch (\Exception $e) {
                    DB::rollBack();

                    $response['message'] = $e->getMessage();
                }

            });
        }

        return json_encode($response);
    }

    public function postAtualizacaoMassa(Request $request)
    {
        $response['status'] = false;

        $sql = Ofertas::select(
            [
                'ofertas.*',
            ]
        );

        $data_pesquisa = $request->get('pesquisa');
        if (!empty($data_pesquisa['repetir_semana'])) {
            $data_pesquisa['repetir_semana'] = explode(",", $data_pesquisa['repetir_semana']);
        }

        $sql = $this->getSearchGrid($sql, $data_pesquisa)->get();

        if (!empty($sql[0])) {
            $ofertas = array_column($sql->toArray(), 'id');

            DB::transaction(function () use ($ofertas, $request, &$response) {

                try {
                    $rows = $request->get('campos');

                    $_update = [];
                    if (!empty($rows['quantidade'])) {
                        $_update['quantidade'] = $rows['quantidade'];
                    }

                    if (isset($rows['aberta']) && strlen($rows['aberta']) > 0 && in_array($rows['aberta'], [0, 1])) {
                        $_update['aberta'] = $rows['aberta'];
                    }

                    if (!empty($rows['data_aprovacao'])) {
                        $_update['data_aprovacao'] = $rows['data_aprovacao'];
                    }

                    if (!empty($rows['status'])) {
                        $_update['status'] = $rows['status'];
                    }

                    if (!empty($rows['equipamento']) && intval($rows['equipamento']) != 0) {
                        $_update['equipamento'] = $rows['equipamento'];
                    }

                    if (!empty($rows['horario_inicial'])) {
                        $_update['hora_inicial'] = $rows['horario_inicial'];
                    }

                    if (!empty($rows['horario_final'])) {
                        $_update['hora_final'] = $rows['horario_final'];
                    }

                    if (!empty($rows['classificacao'])) {
                        $_update['classificacao'] = $rows['classificacao'];
                    }

                    if (!empty($rows['observacao'])) {
                        $_update['observacao'] = $rows['observacao'];
                    }

                    Ofertas::whereIn('id', $ofertas)->update($_update);

                    DB::commit();

                    $response['status'] = true;
                    $response['data'] = $_update;
                } catch (\Exception $e) {
                    DB::rollBack();

                    $response['message'] = $e->getMessage();
                }

            });

        }

        return json_encode($response);
    }

    public function getRelatorio()
    {
        $view = View("admin.{$this->layout}.relatorio.index");
        $view->entry = null;

        return $view;
    }

    public function getRelatorioCsv()
    {
        $view = View("admin.{$this->layout}.relatorio-csv.index");
        $view->entry = null;

        return $view;
    }

    public function postRelatorio(OfertaRelatorioRequest $request)
    {
        ini_set('memory_limit', '2G');

        $view = View("admin.{$this->layout}.relatorio.data");

        $data = Ofertas::getRelatorioCompleto($request->all());

        try {
            if (empty($data[0])) {
                throw new \Exception("Sem dados");
            }

            $path = PATH_FILE_RELATORIO . 'excel/ofertas/' . Util::getUser() . '/';
            $filename = "relatorio-ofertas-{$request->get('mes')}";

            Excel::create($filename, function ($excel) use ($data, $request) {

                $excel->sheet("DATA", function ($sheet) use ($data, $request) {
                    $header = ['Mês', 'Unidade', 'Natureza', 'Especialidade', 'Procedimentos', 'Equipamento', 'Médico', 'Observação', 'Semana', 'Dia', 'Hora (Inicio)', 'Hora (Final)', 'Periodo', 'Tempo', 'Pacientes', 'Intervalo', 'Abertura (SIGA)', 'Status', 'Data', 'Prazo', 'Aberta', 'Classificação', 'Ocorrências'];

                    $_data[0] = $header;
                    $i = 2;

                    try {
                        foreach ($data as $k => $row) {
                            $_k = $k + 1;

                            $_data[$_k]['mes'] = "=UPPER(TEXT(S{$i}, \"MMMM\"))";
                            $_data[$_k]['unidadde'] = $row->arena;
                            $_data[$_k]['natureza'] = $row->natureza;
                            $_data[$_k]['especialidade'] = $row->especialidade;
                            $_data[$_k]['procedimentos'] = implode("\r\n", array_filter(explode(";", $row->procedimentos), 'nl2br'));
                            $_data[$_k]['equipamento'] = $row->equipamento;
                            $_data[$_k]['medico'] = $row->medico;
                            $_data[$_k]['observacao'] = $row->observacao;
                            $_data[$_k]['semana'] = $row->semana;
                            $_data[$_k]['dia'] = $row->dia;
                            $_data[$_k]['hora-inicial'] = $row->hora_inicial;
                            $_data[$_k]['hora-final'] = $row->hora_final;
                            $_data[$_k]['periodo'] = $row->periodo;
                            $_data[$_k]['tempo'] = "=(L{$i}-K{$i})";
                            $_data[$_k]['paciente'] = $row->quantidade;
                            $_data[$_k]['intervalo'] = "=(N{$i}/O{$i})";
                            $_data[$_k]['abertura'] = ($row->data_aprovacao != "00/00/0000") ? $row->data_aprovacao : null;
                            $_data[$_k]['status'] = $row->status;
                            $_data[$_k]['data'] = $row->data;
                            $_data[$_k]['prazo'] = "=IF(Q{$i}>0,S{$i}-Q{$i},\"\")";
                            $_data[$_k]['aberta'] = $row->aberta;
                            $_data[$_k]['classificacao'] = $row->classificacao;
                            $_data[$_k]['ocorrencias'] = implode("\r\n", array_filter(explode(";", $row->ocorrencias), 'nl2br'));

                            $i++;
                        }

                        $sheet->setColumnFormat(array(
                            'E' => '@',
                            'T' => '00',
                            'J' => '00',
                            'O' => '##0',
                            'K' => 'hh:mm',
                            'L' => 'hh:mm',
                            'N' => 'hh:mm',
                            'P' => 'hh:mm',
                            'Q' => 'dd/mm/yyyy',
                            'S' => 'dd/mm/yyyy',
                        ));

                        $style = array(
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                'wrap' => true
                            )
                        );

                        $sheet->getStyle('E')->applyFromArray($style);
                        $sheet->getStyle('V')->applyFromArray($style);

                        $sheet->fromArray($_data, null, 'A1', false, false);

                        $sheet->setAutoFilter('A1:W1');
                        $sheet->setFreeze('A2');

                    } catch (\Exception $e) {
                        exit("<pre> => " . print_r($e->getMessage(), true) . "</pre>");
                    }

                });

            })->store('xlsx', public_path($path));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            $view->link = null;
        }

        return $view;
    }

    public function getGrid(OfertaPesquisaRequest $request)
    {
        $view = View("admin.{$this->layout}.grid");

        $sql = Ofertas::select(
            [
                'ofertas.id',
                'ofertas.codigo',
                'ofertas.equipamento',
                'ofertas.quantidade',
                'ofertas.data_aprovacao',
                'ofertas.data',
                'ofertas.hora_inicial',
                'ofertas.hora_final',
                'ofertas.periodo',
                'ofertas.aberta',
                'ofertas.status',
                'ofertas.observacao',
                'ofertas.classificacao',
                'arenas.nome AS arena',
                'linha_cuidado.nome AS linha_cuidado',
                'profissionais.nome AS profissional',
                DB::raw("TIME_FORMAT(SEC_TO_TIME(TIMESTAMPDIFF(MINUTE, ofertas.hora_inicial, ofertas.hora_final) / ofertas.quantidade* 60), '%H:%i') AS intervalo"),
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'ofertas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'ofertas.linha_cuidado')
            ->join('profissionais', 'profissionais.id', '=', 'ofertas.profissional');

        $sql = $this->getSearchGrid($sql, $request->all());

        $sql->orderBy('ofertas.id', 'desc');

        $view->grid = $sql->paginate(30);

        $view->update_mass = $request->all();

        return $view;
    }

    public function getSearchGrid($sql, $data)
    {
        $date['start'] = Util::Date2DB($data['data-inicial']);
        $date['end'] = Util::Date2DB($data['data-final']);

        $sql->whereBetween('ofertas.data', [$date['start'], $date['end']]);

        if (!empty($data['linha_cuidado'])) {
            $sql->where('ofertas.linha_cuidado', $data['linha_cuidado']);
        }

        if (!empty($data['profissional'])) {
            $sql->where('ofertas.profissional', $data['profissional']);
        }

        if (!empty($data['classificacao'])) {
            $sql->where('ofertas.classificacao', $data['classificacao']);
        }

        if (!empty($data['equipamento'])) {
            $sql->where('ofertas.equipamento', $data['equipamento']);
        }

        if (strlen($data['aberta']) > 0 && in_array($data['aberta'], [0, 1])) {
            $sql->where('ofertas.aberta', $data['aberta']);
        }

        if (!empty($data['status'])) {
            $sql->where('ofertas.status', $data['status']);
        }

        if (!empty($data['unidade'])) {
            $sql->where('ofertas.arena', $data['unidade']);
        }

        if (!empty($data['horario-inicial'])) {
            $sql->where('ofertas.hora_inicial', ">=", $data['horario-inicial']);
        }

        if (!empty($data['horario-final'])) {
            $sql->where('ofertas.hora_final', "<=", $data['horario-final']);
        }

        if (!empty($data['intervalo'])) {
            $sql->whereRaw(DB::raw("TIME_FORMAT(SEC_TO_TIME(TIMESTAMPDIFF(MINUTE, ofertas.hora_inicial, ofertas.hora_final) / ofertas.quantidade* 60), '%H:%i') = '{$data['intervalo']}'"));
        }

        if (!empty($data['procedimento'])) {
            $sql->join('oferta_procedimentos', 'oferta_procedimentos.oferta', '=', 'ofertas.id');
            $sql->where('oferta_procedimentos.procedimento', $data['procedimento']);
        }

        if (!empty($data['repetir_semana']) && count($data['repetir_semana']) > 0) {
            $sql->whereRaw(DB::raw("DATE_FORMAT(ofertas.data,'%w')") . " IN (" . implode(',', $data['repetir_semana']) . ")");
        }

        return $sql;
    }

    public function getList()
    {
        $view = View("admin.{$this->layout}.list")->with('title', $this->title);

        $view->entry = null;
        $view->update_mass = null;

        return $view;
    }

    public function postRemover(Request $request)
    {
        $response['status'] = false;

        try {
            $oferta = Ofertas::find($request->get('oferta'));
            $oferta->delete();

            $response['status'] = true;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function getOcorrencias($oferta)
    {
        $view = View("admin.{$this->layout}.ocorrencias")->with('title', $this->title);

        $view->oferta = Ofertas::find($oferta);
        $view->entry = $view->oferta;

        return $view;
    }

    public function postOcorrencias(OfertaOcorrenciasRequest $request)
    {
        try {
            $ocorrencias = new OfertaOcorrencias();
            $ocorrencias->oferta = $request->get('oferta');
            $ocorrencias->descricao = $request->get('descricao');
            $ocorrencias->save();

            Ofertas::setOfertaStatus($request->get('oferta'), $request->get('status'));
            $oferta = Ofertas::find($request->get('oferta'));

            $response['status'] = true;
            $response['data'] = $oferta;
            $response['status_descricao'] = \App\Http\Helpers\DataHelpers::getOfertaStatus($oferta->status);
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function getStatus($oferta)
    {
        $view = View("admin.{$this->layout}.status")->with('title', $this->title);

        $view->oferta = Ofertas::find($oferta);
        $view->entry = $view->oferta;

        return $view;
    }

    public function postStatus(OfertaStatusRequest $request)
    {
        $response['status'] = false;

        try {
            $oferta = Ofertas::find($request->get('oferta'));
            $oferta->status = $request->get('status');
            $oferta->aberta = $request->get('aberta');
            $oferta->save();

            Ofertas::setOfertaStatus($oferta->id, $request->get('status'));

            $response['status'] = true;
            $response['data'] = $oferta;
            $response['status_descricao'] = \App\Http\Helpers\DataHelpers::getOfertaStatus($oferta->status);
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function getAprovacao($oferta)
    {
        $view = View("admin.{$this->layout}.aprovacao")->with('title', $this->title);

        $view->oferta = Ofertas::find($oferta);
        $view->entry = $view->oferta;

        if (!empty($view->entry->data_aprovacao)) {
            $view->entry->data_aprovacao = Util::DB2User($view->entry->data_aprovacao);
        }

        if (empty($view->entry->data_aprovacao)) {
            $view->entry->data_aprovacao = date('d/m/Y');
        }

        return $view;
    }

    public function postAprovacao(OfertaAprovacaoRequest $request)
    {
        $response['status'] = false;

        try {
            $oferta = Ofertas::find($request->get('oferta'));
            $oferta->data_aprovacao = $request->get('data_aprovacao');
            $oferta->save();

            Ofertas::setOfertaStatus($oferta->id, $request->get('status'));

            $response['status'] = true;
            $response['data'] = $oferta;
            $response['status_descricao'] = \App\Http\Helpers\DataHelpers::getOfertaStatus($request->get('status'));
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function postIndex(OfertasRequest $request)
    {
        $response['status'] = false;

        try {
            $data = $request->all();

            Ofertas::saveOfertas($data);

            $response['status'] = true;

            $_date = explode("/", $data['data']);
            $periodo = Util::periodoMesPorAnoMes($_date[2], $_date[1]);

            $data['data_final'] = Util::DB2User(explode(" ", $periodo['end'])[0]);

            $response['data'] = $data;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function getRegistro($id = null)
    {
        $view = View("admin.{$this->layout}.registro");

        $entry = null;
        if (!is_null($id)) {
            $entry = $this->objModel->find($id);
            $entry->data = Util::DB2User($entry->data);

            if (!empty($entry->data_aprovacao) && $entry->data_aprovacao == "0000-00-00") {
                $entry->data_aprovacao = null;
            }

            if (!empty($entry->data_aprovacao)) {
                $entry->data_aprovacao = Util::DB2User($entry->data_aprovacao);
            }

            $procedimentos = Ofertas::getProcedimentos($id);
            if (!empty($procedimentos[0])) {
                $entry->procedimentos = array_column($procedimentos->toArray(), 'procedimento');
            }

            $view->linha_cuidado = LinhaCuidado::ByArena($entry->arena);
            $view->equipamentos = ArenaEquipamentos::getByArena($entry->arena);
            $view->profissionais = Profissionais::ComboByLinhaCuidado($entry->linha_cuidado);
            $view->procedimentos = Procedimentos::ComboByLinhaCuidado($entry->linha_cuidado);
        }

        $view->entry = $entry;

        return $view;
    }

}
