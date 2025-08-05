<?php

namespace App\Http\Controllers;

use App\Agendas;
use App\Arenas;
use App\AtendimentoLaudo;
use App\Atendimentos;
use App\AtendimentoTempo;
use App\Faturamento;
use App\Http\Helpers\BI;
use App\Http\Helpers\Biopsia\RelatorioExcel;
use App\Http\Helpers\Exportacao\ExportacaoArquivosHelpers;
use App\Http\Helpers\Relatorios;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Http\Requests\Relatorio\AgendamentoRemarcacaoRequest;
use App\Http\Requests\Relatorio\EstatisticaRequest;
use App\Http\Requests\Relatorio\FaturamentoLinhaCuidadoRequest;
use App\Http\Requests\Relatorio\FaturamentoProcedimentoMedicoRequest;
use App\Http\Requests\Relatorio\MonitorFaturamentoRequest;
use App\Http\Requests\Relatorio\PrevisaoFaturamentoRequest;
use App\Http\Requests\Relatorio\ProducaoMedicosRequest;
use App\Http\Requests\Relatorio\RelatorioContasConsultaRequest;
use App\Http\Requests\Relatorio\RelatorioFPOsRequest;
use App\Http\Requests\Relatorio\RelatorioGorduraFaturamentoRequest;
use App\Http\Requests\Relatorio\RelatorioOfertaProducaoRequest;
use App\Http\Requests\Relatorio\RelatoriosProcedimentosAgendaFaltaRequest;
use App\LinhaCuidado;
use App\Lotes;
use App\Procedimentos;
use App\Usuarios;
use Carbon\Carbon;
use ConsoleTVs\Charts\Facades\Charts;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_NumberFormat;

class RelatorioController extends Controller
{
    public function getContasConsulta()
    {
        $view = View('admin.relatorios.contas-consulta.index');

        return $view;
    }

    public function postContasConsulta(RelatorioContasConsultaRequest $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600);

        $periodo['start'] = Carbon::createFromFormat('d/m/Y', $request->get('periodo_inicial'))->toDateString() . " 00:00:00";
        $periodo['end'] = Carbon::createFromFormat('d/m/Y', $request->get('periodo_final'))->toDateString() . " 23:59:59";

        $view = View('admin.relatorios.contas-consulta.data');

        $data = Relatorios::getAtendimentoContasConsulta($periodo, $request->get('arena'), $request->get('linha_cuidado'));
        $data_consulta = Relatorios::getAtendimentoContasConsultaDiagnostico($periodo, $request->get('arena'), $request->get('linha_cuidado'));

        try {
            if (empty($data[0]) && empty($data_consulta[0])) {
                throw new \Exception("Sem dados");
            }

            $path = PATH_FILE_RELATORIO . 'excel/contas-consulta/' . Util::getUser() . '/';
            $filename = "relatorio-contas-consulta";

            Excel::create($filename, function ($excel) use ($data, $data_consulta, $request, $periodo) {

                $excel->sheet("CONSULTA CIRURGICA", function ($sheet) use ($data, $request) {
                    $sheet->loadView('admin.relatorios.contas-consulta.consulta-cirurgica')->with('relatorio', $data)->with('params', $request->all());

                    $sheet->setColumnFormat(array(
                        'G' => '00',
                        'H' => '[$R$ ]* #,##0.00_-',
                        'I' => '[$R$ ]* #,##0.00_-',
                    ));

                    $sheet->setAutoFilter('A1:G1');
                    $sheet->setFreeze('A2');

                });

                $excel->sheet("DIAGNOSTICO", function ($sheet) use ($data_consulta, $request) {
                    $sheet->loadView('admin.relatorios.contas-consulta.diagnostico')->with('relatorio', $data_consulta)->with('params', $request->all());

                    $sheet->setColumnFormat(array(
                        'F' => '00',
                        'G' => '00',
                        'H' => '[$R$ ]* #,##0.00_-',
                        'I' => '[$R$ ]* #,##0.00_-',
                    ));

                    $sheet->setAutoFilter('A1:G1');
                    $sheet->setFreeze('A2');

                });

            })->store('xlsx', public_path($path));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            exit("<pre>" . print_r($e->getMessage(), 1) . "</pre>");
            $view->link = null;
        }

        return $view;
    }

    public function getConduta()
    {
        $view = View('admin.bi.relatorios.conduta.index');

        return $view;
    }

    public function getAtendimentoCondutas()
    {
        $view = View('admin.relatorios.atendimento-condutas.index');

        return $view;
    }

    public function postAtendimentoCondutas(RelatorioContasConsultaRequest $request)
    {
        $periodo['start'] = Carbon::createFromFormat('d/m/Y', $request->get('periodo_inicial'))->toDateString() . " 00:00:00";
        $periodo['end'] = Carbon::createFromFormat('d/m/Y', $request->get('periodo_final'))->toDateString() . " 23:59:59";

        $html = null;
        try {
            $data = Relatorios::getAtendimentoCondutas($periodo, $request->get('arena'), $request->get('linha_cuidado'));

            if (!empty($data[0])) {
                $headers = ['ARENA', 'DATA ATENDIMENTO', 'NOME DO PACIENTE', 'CNS', 'ESPECIALIDADE', 'MÉDICO', 'TIPO DE ATENDIMENTO', 'CONDUTA PRINCIPAL', 'CONDUTA SECUNDÁRIA', 'REGULAÇÃO', 'LATERALIDADE', 'DESCRIÇÃO'];
                $lines[] = implode(";", $headers);
                foreach ($data->toArray() as $row) {
                    $row['descricao'] = str_replace("\n", " ", $row['descricao']);
                    $lines[] = implode(";", $row);
                }

                $path = PATH_FILE_RELATORIO . 'excel/atendimentos_condutas/' . Util::getUser() . '/';
                Upload::recursive_mkdir($path);
                $filename = "atendimentos-condutas.csv";
                file_put_contents(public_path($path . $filename), implode("\r\n", $lines));

                $link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;

                $html = '<div class="alert alert-success text-left"><a href="' . $link . '" target="_blank" ><strong>Clique aqui</strong></a> para fazer download do arquivo.</div>';
            } else {
                throw new \Exception("Não existe dados ou informações para gerar o relatório.");
            }

        } catch (\Exception $e) {
            $html = "<div class='alert alert-danger text-left'>Não foi possível gerar o relatótio.<br />" . $e->getMessage() . "</div>";
            $link = null;
        }

        return $html;
    }

    public function getOfertaProducao()
    {
        $view = View('admin.relatorios.oferta-producao.index');

        return $view;
    }

    public function postOfertaProducao(RelatorioOfertaProducaoRequest $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);

        $periodo['start'] = Carbon::createFromFormat('d/m/Y', $request->get('periodo_inicial'))->toDateString() . " 00:00:00";
        $periodo['end'] = Carbon::createFromFormat('d/m/Y', $request->get('periodo_final'))->toDateString() . " 23:59:59";

        $data = Relatorios::getAbsenteismoPerdaPrimariaAgenda($periodo, $request->get('arena'), $request->get('linha_cuidado'));
        if (!is_null($data)) {
            $link = ExportacaoArquivosHelpers::getAbsenteismoPerdaPrimariaAgenda($data);
        }

        $html = '<div class="alert alert-danger text-left">Não foi possível gerar o relatótio.</div>';
        if (!empty($link)) {
            $html = '<div class="alert alert-success text-left"><a href="' . $link . '" target="_blank" ><strong>Clique aqui</strong></a> para fazer download do arquivo.</div>';
        }

        return $html;
    }

    public function postCondutaGrid(Request $request)
    {
        $view = View('admin.bi.relatorios.conduta.grid');

        $params = Input::all();

        $view->arena = $request->get('arena');
        $view->linha_cuidado = $request->get('linha_cuidado');
        $view->date = $request->get('data');
        $view->medico = $request->get('medico');
        $view->digitador = $request->get('digitador');

        return $view;
    }

    public function getCondutaData()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);

        $params = Input::all();

        $view = (!$params['detalhado']) ? View('admin.bi.relatorios.conduta.data') : View('admin.bi.relatorios.conduta.data-detalhado');

        $view->arena = $params['arena'];
        $view->linha_cuidado = $params['linha_cuidado'];
        $view->date = $params['date'];
        $view->profissional = $params['profissional'];
        $view->detalhado = 0;

        $options = new Options();

        $dompdf = new Dompdf($options);

        $view->canvas = $canvas = $dompdf->getCanvas();
        $contents = $view->render();

        $dompdf->loadHtml($contents);

        $dompdf->setPaper('A4');

        $dompdf->render();

        $dompdf->get_canvas()->page_text(532, 798, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));

        $nome_arquivo = 'relatorio-producao';
        $dompdf->stream($nome_arquivo, array("Attachment" => false));
        die;
    }

    public function getRemarcacao()
    {
        $view = View('admin.relatorios.agendas.remarcacao.index');

        return $view;
    }

    public function postRemarcacao(AgendamentoRemarcacaoRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.agendas.remarcacao.data');

        $data = Agendas::getRermacacao($request->get('ano'), $request->get('mes'), $request->get('unidade'));

        try {
            if (empty($data[0])) {
                throw new \Exception("Sem dados");
            }

            $path = PATH_FILE_RELATORIO . 'excel/agenda/remarcacao/' . Util::getUser() . '/';
            $filename = "relatorio-agenda-remarcacao__{$request->get('ano')}-{$request->get('mes')}";

            Excel::create($filename, function ($excel) use ($data, $request) {

                $excel->sheet("REMARCAÇÃO", function ($sheet) use ($data, $request) {
                    $sheet->loadView('admin.relatorios.agendas.remarcacao.data-excel')->with('relatorio', $data)->with('params', $request->all());

                    $sheet->setColumnFormat(array(
                        'D' => '00',
                    ));

                    $sheet->setAutoFilter('A1:I1');
                    $sheet->setFreeze('A2');

                });

            })->store('xlsx', public_path($path));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            $view->link = null;
        }

        return $view;

    }

    public function getMedicosProducao()
    {
        $view = View('admin.relatorios.producao.medicos.index');

        return $view;
    }

    public function getFaturamentoDetalhado()
    {
        $view = View('admin.relatorios.faturamento.detalhado.index');

        return $view;
    }

    public function getProducaoUnidades()
    {
        $view = View('admin.relatorios.producao.unidades.index');

        return $view;
    }

    public function postFaturamentoDetalhado(MonitorFaturamentoRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.faturamento.detalhado.data');

        try {
            $data = Faturamento::getFaturamentoDetalhes($request->get('contrato'), $request->get('faturamento'));

            $headers = ['DATA', 'UNIDADE', 'ESPECIALIDADE', 'MEDICO', 'COMPLEXIDADE', 'CÓD PROCEDIMENTO', 'PROCEDIMENTO', 'QUANTIDADE', 'VALOR UNITARIO'];
            $lines[] = implode(";", $headers);
            foreach ($data->toArray() as $row) {
                $lines[] = implode(";", $row);
            }

            $path = PATH_FILE_RELATORIO . 'excel/faturamentos/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);
            $filename = "relatorio-faturamento.csv";
            file_put_contents(public_path($path . $filename), implode("\r\n", $lines));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;
        } catch (\Exception $e) {
            $view->link = null;
        }

        return $view;
    }

    public function postMedicosProducao(ProducaoMedicosRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.producao.medicos.data');

        $data = Relatorios\ProducaoHelpers::getProducaoMedicos($request->get('ano'), $request->get('mes'), $request->get('arena'), $request->get('medico'));

        try {
            if (empty($data[0])) {
                throw new \Exception("Sem dados");
            }

            $path = PATH_FILE_RELATORIO . 'excel/producao/medicos/' . Util::getUser() . '/';
            $filename = "relatorio-producao-medica__{$request->get('ano')}-{$request->get('mes')}";

            Excel::create($filename, function ($excel) use ($data, $request) {

                $excel->sheet("PRODUÇÃO MÉDICA", function ($sheet) use ($data, $request) {
                    $sheet->loadView('admin.relatorios.producao.medicos.data-excel')->with('relatorio', $data)->with('params', $request->all());

                    $sheet->setColumnFormat(array(
                        'C' => '00',
                        'K' => '00',
                        'L' => '00'
                    ));

                    $sheet->setAutoFilter('A1:L1');
                    $sheet->setFreeze('A2');

                });

            })->store('xlsx', public_path($path));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            $view->link = null;
        }

        return $view;
    }

    public function getMonitorFaturamento()
    {
        $view = View('admin.relatorios.faturamento.producao.index');


        return $view;
    }

    public function postMonitorFaturamento(MonitorFaturamentoRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.faturamento.producao.data');

        $faturamento = Faturamento::find($request->get('faturamento'));

        $contrato = $request->get('contrato');
        $linha_cuidado = $request->get('linha_cuidado');

        $periodo = Util::periodoMesPorAnoMes($faturamento->ano, $faturamento->mes);

        if (empty($linha_cuidado)) {
            $linhas_cuidado = LinhaCuidado::Combo();
        } else {
            $linhas_cuidado[$linha_cuidado] = null;
        }

        try {

            $data = [];
            foreach ($linhas_cuidado as $k_linha_cuidado => $linha_cuidado) {

                $procedimentos = Procedimentos::whereIn('id', Procedimentos::getProcedimentosIDByLinhaCuidado($k_linha_cuidado))->get();
                if (!empty($procedimentos[0])) {
                    $data[$k_linha_cuidado] = [
                        'linha_cuidado' => LinhaCuidado::get($k_linha_cuidado),
                        'procedimentos' => $procedimentos->toArray(),
                    ];

                    $data_linha_cuidado[$k_linha_cuidado] = $data[$k_linha_cuidado]['linha_cuidado']['nome'];
                }
            }

            $path = PATH_FILE_RELATORIO . 'excel/faturamentos/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);
            $filename = "relatorio-faturamento-producao";

            Excel::create($filename, function ($excel) use ($data, $linhas_cuidado, $periodo, $contrato, $faturamento, $data_linha_cuidado) {

                $excel->sheet("PROCEDIMENTOS", function ($sheet) use ($data, $linhas_cuidado, $periodo, $contrato, $faturamento) {
                    $sheet->loadView('admin.relatorios.faturamento.producao.data-excel')->with('relatorio', $data)->with('linhas_cuidado', $linhas_cuidado)->with('periodo', $periodo)->with('contrato', $contrato)->with('faturamento', $faturamento);

                    $sheet->setColumnFormat(array(
                        'D' => '[$R$ ]* #,##0.00_-',
                        'H' => '[$R$ ]* #,##0.00_-',
                        'E' => '0',
                        'F' => '0',
                        'G' => '0',
                    ));
                });

                $excel->sheet("ESPECIALIDADE", function ($sheet) use ($data_linha_cuidado) {
                    $sheet->loadView('admin.relatorios.faturamento.producao.data-excel-linha-cuidado')->with('linha_cuidado', $data_linha_cuidado);

                    $sheet->setColumnFormat(array(
                        'E' => '[$R$ ]* #,##0.00_-',
                        'B' => '0',
                        'C' => '0',
                        'D' => '0',
                    ));
                });

            })->store('xlsx', public_path($path));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            exit("<pre>" . print_r($e->getMessage(), true) . "</pre>");
            $view->link = null;
        }

        return $view;
    }

    public function getFpos()
    {
        $view = View('admin.relatorios.exportacao.fpos.index');

        return $view;
    }

    public function getEstatistica()
    {
        $view = View('admin.relatorios.estatistica.index');

        return $view;
    }

    public function postEstatistica(EstatisticaRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.estatistica.data');
        $ano = $request->get('ano');
        $mes = $request->get('mes');
        $arena = (object)Arenas::get($request->get('arena'));
        $tipo = $request->get('tipo');

        try {
            $data_genero = Relatorios\EstatisticasHelpers::getGenero($ano, $mes, $arena->id, $tipo);

            if (empty($data_genero)) {
                throw new \Exception("Nenhuma informação encontrada!");
            }

            $report = null;
            if (!empty($data_genero)) {
                foreach ($data_genero as $row) {
                    $report[$row->abreviacao][$row->nome][$row->sexo][intval($row->data_agendamento)] = $row->total;
                }
            }

            $data_idade = Relatorios\EstatisticasHelpers::getIdade($ano, $mes, $arena->id, $tipo);

            $report_idade = null;
            if (!empty($data_idade)) {
                foreach ($data_idade as $row) {
                    $report_idade[$row->linha_cuidado][$row->medico][$row->idade][intval($row->dia)] = $row->total;
                }
            }

            $path = PATH_FILE_RELATORIO . 'excel/estatistica/' . Util::getUser() . '/';
            $filename = "relatorio-estatistica__" . snake_case(strtolower(Util::RemoveAcentos($arena->nome)));

            Excel::create($filename, function ($excel) use ($report_idade, $report, $mes, $ano, $request, $arena, $tipo) {

                $excel->sheet("GÊNERO", function ($sheet) use ($report, $request, $arena, $tipo) {
                    $sheet->loadView('admin.relatorios.estatistica.data-excel-genero')->with('report', $report)->with('mes', $request->get('mes'))->with('ano',
                        $request->get('ano'))->with('arena', $arena)->with('tipo', $tipo);

                    $sheet->setColumnFormat(
                        array(
                            'D:AH' => '0'
                        ));

                    $sheet->setFreeze('A5');
                });

                $excel->sheet("IDADE", function ($sheet) use ($report_idade, $mes, $ano, $arena, $tipo) {
                    $sheet->loadView('admin.relatorios.estatistica.data-excel-idade')->with('report', $report_idade)->with('mes', $mes)->with('ano', $ano)->with('arena',
                        $arena)->with('tipo', $tipo);

                    $sheet->setColumnFormat(
                        array(
                            'D:AH' => '0'
                        ));

                    $sheet->setFreeze('A5');
                });

            })->store('xlsx', public_path($path));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            exit("<pre>" . print_r($e->getMessage(), true) . "</pre>");
            $view->link = null;
        }

        return $view;
    }

    public function postFpos(RelatorioFPOsRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.exportacao.fpos.data');

        try {
            $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $lote = Lotes::find($request->get('contrato'));

            $arenas = Lotes::getArenas([$lote->id]);

            $procedimentos = \App\Procedimentos::getByArenas($arenas);
            $filename = "relatorio-fpos";

            if (count($procedimentos) > 0) {
                $data = null;
                foreach ($procedimentos as $row) {
                    $data[$row->id] = $row->toArray();
                }

                $_procedimentos_contrato = \App\ContratoProcedimentos::getContratoProcedimentoByContratoProcedimento(array_keys($data));
                foreach ($_procedimentos_contrato as $row) {
                    if (array_key_exists($row->procedimento, $data)) {
                        $data[$row->procedimento] = array_merge($row->toArray(), $data[$row->procedimento]);
                    }
                }

                try {
                    Excel::create($filename, function ($excel) use ($data) {

                        $excel->sheet("RESUMO", function ($sheet) use ($data) {
                            $sheet->loadView('admin.relatorios.exportacao.fpos.resumo-excel');
                            $sheet->setColumnFormat(array(
                                'B' => '[$R$ ]* #,##0.00_-',
                                'C2:C12' => '[$R$ ]* #,##0.00_-',
                                'C14' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                            ));
                        });

                        $excel->sheet("FPOS", function ($sheet) use ($data) {
                            $sheet->loadView('admin.relatorios.exportacao.fpos.data-excel')->with('procedimentos', $data);
                            $sheet->setColumnFormat(array(
                                'E' => '[$R$ ]* #,##0.00_-',
                                'G' => '[$R$ ]* #,##0.00_-'
                            ));
                            $sheet->setAutoFilter('A1:G1');
                            $sheet->setFreeze('A2');
                        });

                    })->store('xlsx', public_path($path));

                    $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }

        return $view;
    }

    public function getAtendimentoNaoFaturado()
    {
        $view = View('admin.relatorios.atendimentos.nao-faturado.index');

        return $view;
    }

    public function postAtendimentoNaoFaturado(Request $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.atendimentos.nao-faturado.data');

        try {
            $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $atendimentos = Atendimentos::getNaoFaturados($request->get('arena'), $request->get('procedimento'), $request->get('medico'));
            $filename = "relatorio-atendimentos-nao-faturado";

            if (count($atendimentos) > 0) {
                try {
                    Excel::create($filename, function ($excel) use ($atendimentos) {

                        $excel->sheet("NÃO FATURADOS", function ($sheet) use ($atendimentos) {
                            $sheet->setAutoFilter('A1:F1');
                            $sheet->setFreeze('A1');
                            $sheet->loadView('admin.relatorios.atendimentos.nao-faturado.excel-nao-faturados')->with('atendimentos', $atendimentos);
                        });

                        $_atendimentos = null;
                        $linhas = 0;
                        foreach ($atendimentos as $row) {
                            if (!empty($row->data)) {
                                $_atendimentos['data'][Util::DBTimestamp2UserDate($row->data)] = null;
                            }

                            if (!empty($row->nome)) {
                                $_atendimentos['arena'][$row->nome] = null;
                            }

                            if (!empty($row->linha_cuidado)) {
                                $_atendimentos['linha_cuidado'][$row->linha_cuidado] = null;
                            }

                            if (!empty($row->procedimento_nome)) {
                                $_atendimentos['procedimento_nome'][$row->procedimento_nome] = null;
                            }
                            if (!empty($row->medico)) {
                                $_atendimentos['medico'][$row->medico] = null;
                            }

                            $linhas++;
                        }

                        $_data = null;
                        foreach ($_atendimentos['data'] as $k => $row) {
                            $_data['data'][] = $k;
                        }

                        foreach ($_atendimentos['arena'] as $k => $row) {
                            $_data['arena'][] = $k;
                        }

                        foreach ($_atendimentos['linha_cuidado'] as $k => $row) {
                            $_data['linha_cuidado'][] = $k;
                        }

                        foreach ($_atendimentos['procedimento_nome'] as $k => $row) {
                            $_data['procedimento_nome'][] = $k;
                        }

                        foreach ($_atendimentos['medico'] as $k => $row) {
                            $_data['medico'][] = $k;
                        }

                        $excel->sheet("RELATORIO", function ($sheet) use ($_data, $linhas) {
                            $sheet->loadView('admin.relatorios.atendimentos.nao-faturado.excel-relatorios')->with('atendimentos', $_data)->with('linhas', $linhas);
                        });

                    })->store('xlsx', public_path($path));

                    $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }

        return $view;
    }

    public function postAgendaProducao(RelatoriosProcedimentosAgendaFaltaRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.bi.relatorios.procedimentos.agenda.data');
        $view->params = $params = $request->all();
        $lote = $params['contrato'];

        try {
            $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $faturamento = Faturamento::find($params['faturamento']);
            $filename = "procedimentos-sub-grupo-{$faturamento->ano}-{$faturamento->mes}";

            if (empty($params['profissional'])) {
                try {
                    Excel::create($filename, function ($excel) use ($params) {

                        $excel->sheet("SUB-GRUPOS (Procedimentos)", function ($sheet) use ($params) {

                            $sheet->setColumnFormat(array(
                                'D' => '[$R$ ]* #,##0.00_-',
                                'E' => '[$R$ ]* #,##0.00_-',
                                'H' => '[$R$ ]* #,##0.00_-',
                                'G' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'J' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'K' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'L' => '[$R$ ]* #,##0.00_-',
                                'N' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'O' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'P' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'Q' => '[$R$ ]* #,##0.00_-',
                                'S' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'T' => '[$R$ ]* #,##0.00_-',
                                'V' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'W' => '[$R$ ]* #,##0.00_-',
                                'Y' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'Z' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'AA' => '[$R$ ]* #,##0.00_-',
                            ));

                            $sheet->loadView('admin.bi.relatorios.procedimentos.agenda.exportacao.excel')->with('params', $params);
                        });

                    })->store('xlsx', public_path($path));

                    $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
                } catch (\Exception $e) {

                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }

        return $view;
    }

    public function getAgendaProducao()
    {
        $view = View('admin.bi.relatorios.procedimentos.agenda.index');

        return $view;
    }

    private function validaRelatorioGerencia()
    {
        return in_array(Auth::user()->id, config('cies.menu-presidencial-usuarios-acessso'));
    }

    public function getFaturamentoProcedimentosMedico()
    {
        $view = View('admin.bi.relatorios.procedimentos.medicos.index');


        return $view;
    }

    public function postFaturamentoProcedimentosMedico(FaturamentoProcedimentoMedicoRequest $request)
    {
        $view = View('admin.bi.relatorios.procedimentos.medicos.data');

        $view->params = $request->all();

        return $view;
    }

    public function getProcedimentos()
    {
        $view = View('admin.bi.relatorios.procedimentos');

        return $view;
    }

    public function getFaturamentoSubGrupo()
    {
        $view = View('admin.bi.relatorios.procedimentos.sub-grupo.index');

        if (!$this->validaRelatorioGerencia()) {
            return redirect("/");
        }

        return $view;
    }

    public function postFaturamentoSubGrupo(Request $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $lote = $request->get('lote');
        $faturamento = $request->get('faturamento');

        try {
            $faturamento = Faturamento::find($faturamento);

            $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $filename = "procedimentos-sub-grupo-{$faturamento->ano}-{$faturamento->mes}";

            try {
                Excel::create($filename, function ($excel) use ($lote, $faturamento) {

                    $excel->sheet("SUB-GRUPOS", function ($sheet) use ($lote, $faturamento) {

                        $sheet->setColumnFormat(array(
                            'C' => '[$R$ ]* #,##0.00_-',
                            'D' => '0',
                            'C' => '[$R$ ]* #,##0.00_-',
                            'E' => '[$R$ ]* #,##0.00_-',
                            'G' => '[$R$ ]* #,##0.00_-',
                            'J' => '[$R$ ]* #,##0.00_-',
                            'N' => '[$R$ ]* #,##0.00_-',
                            'H' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                            'K' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                            'L' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,

                        ));

                        $sheet->loadView('admin.bi.relatorios.procedimentos.sub-grupo.data')->with('lote',
                            $lote)->with('faturamento', $faturamento);
                    });

                })->store('xlsx', public_path($path));

            } catch (\Exception $e) {
                print("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getMessage(),
                        1) . "</pre>"); #debug-edersonsandre
                exit("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getLine(),
                        1) . "</pre>"); #debug-edersonsandre
            }

            $data['link'] = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
            $data['download'] = '<div class="alert alert-success"><a href="' . $data['link'] . '" target="_blank" ><strong>Clique aqui</strong></a> para fazer download do arquivo em excel.</div>';

            return $data;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }

        return $view;
    }

    public function getConfiguracoesProcedimentos()
    {
        $view = View('admin.bi.relatorios.configuracoes-procedimentos.index');

        return $view;
    }

    public function postConfiguracoesProcedimentos(Request $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $linha_cuidado = $request->get('linha_cuidado');

        try {

            if (empty($linha_cuidado)) {
                //$linhas_cuidado = LinhaCuidado::getLinhaDiagnostico()->lists('nome', 'id')->toArray();
                $linhas_cuidado = LinhaCuidado::lists('nome', 'id')->toArray();
            } else {
                $linhas_cuidado = LinhaCuidado::where('id', $linha_cuidado)->lists('nome', 'id')->toArray();
            }


            $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $filename = "procedimentos-configuracoes";

            try {
                Excel::create($filename, function ($excel) use ($linhas_cuidado) {

                    $excel->sheet("PROCEDIMENTOS", function ($sheet) use ($linhas_cuidado) {
//
//                        $sheet->setFreeze('A1');
//                        $sheet->setFreeze('A2');
//
//                        $sheet->setColumnFormat(array(
//                            'D' => '0',
//                            'E' => '0.00',
//                            'F' => '0.00',
//                        ));

                        $sheet->loadView('admin.bi.relatorios.configuracoes-procedimentos.data')->with('linhas_cuidado',
                            $linhas_cuidado);
                    });

                })->store('xlsx', public_path($path));

            } catch (\Exception $e) {
                print("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getMessage(),
                        1) . "</pre>"); #debug-edersonsandre
                exit("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getLine(),
                        1) . "</pre>"); #debug-edersonsandre
            }

            $data['link'] = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
            $data['download'] = '<div class="alert alert-success"><a href="' . $data['link'] . '" target="_blank" ><strong>Clique aqui</strong></a> para fazer download do arquivo em excel.</div>';

            return $data;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }

        return $view;
    }

    public function getFaturamentoLinhaCuidado()
    {
        $view = View('admin.bi.relatorios.faturamento-linha-cuidado.index');

        return $view;
    }

    public function postFaturamentoLinhaCuidado(FaturamentoLinhaCuidadoRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $linha_cuidado = $request->get('linha_cuidado');
        $lote = $request->get('lote');
        $faturamento = $request->get('faturamento');

        try {
            $faturamento = Faturamento::find($faturamento);
            if (empty($linha_cuidado)) {
                $linhas_cuidado = LinhaCuidado::Combo();
            } else {
                $linhas_cuidado = LinhaCuidado::where('id', $linha_cuidado)->lists('nome', 'id')->toArray();
            }

            $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $filename = "procedimentos-faturados-linha-cuidad-agenda-{$faturamento->ano}-{$faturamento->mes}";

            try {
                Excel::create($filename, function ($excel) use ($lote, $faturamento, $linhas_cuidado) {

                    $excel->sheet("PROCEDIMENTOS", function ($sheet) use ($linhas_cuidado, $lote, $faturamento) {

                        //$sheet->setFreeze('A1');
                        $sheet->setFreeze('A2');

                        $sheet->setColumnFormat(array(
                            'D' => '0',
                            'H' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE,
                            'E' => '[$R$ ]* #,##0.00_-',
                            'F' => '[$R$ ]* #,##0.00_-',
                            'I' => '[$R$ ]* #,##0.00_-',
                            'K' => '[$R$ ]* #,##0.00_-',
                            'M' => '[$R$ ]* #,##0.00_-',
                            'Q' => '[$R$ ]* #,##0.00_-',
                            'O' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                            'P' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                        ));

                        $sheet->loadView('admin.bi.relatorios.faturamento-linha-cuidado.data')->with('linhas_cuidado',
                            $linhas_cuidado)->with('lote', $lote)->with('faturamento', $faturamento);
                    });

                })->store('xlsx', public_path($path));

            } catch (\Exception $e) {
                print("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getMessage(),
                        1) . "</pre>"); #debug-edersonsandre
                exit("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getLine(),
                        1) . "</pre>"); #debug-edersonsandre
            }

            $data['link'] = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
            $data['download'] = '<div class="alert alert-success"><a href="' . $data['link'] . '" target="_blank" ><strong>Clique aqui</strong></a> para fazer download do arquivo em excel.</div>';

            return $data;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }

        return $view;
    }

    public function getAderenciaDigitador()
    {
        $view = View('admin.bi.relatorios.aderencia.digitador');

        return $view;
    }

    public function postAderenciaDigitador()
    {
        $view = View('admin.bi.relatorios.aderencia.relatorio');
        $params = Input::all();

        $data = Relatorios::aderenciaDigitador($params);

        $chart = Charts::multi('line', 'fusioncharts')
            ->title("Aderência (Produção)")
            ->elementLabel("Quantidades")
            ->dimensions(0, 400)
            ->colors(['#2196F3', '#F44336', '#CCCCCC', '#FF00BF'])
            ->labels(array_keys($data['geral']));

        if (count($data['geral'])) {
            foreach ($data['geral'] as $key => $row) {
                $agendados[] = $row['Agendados'];
                //$atendidos[] = $row['Atendidos'];
                $digitador[] = $row['Digitador'];
                $faturista[] = $row['Faturista'];
                $recepcao[] = $row['Recepcao'];
            }

            $chart->dataset('Agendados', $agendados);
            //$chart->dataset('Atendidos', $atendidos);
            $chart->dataset('Faturista', $faturista);
            $chart->dataset('Digitador', $digitador);
            $chart->dataset('Recepcao', $recepcao);

            $view->graph_all = $chart;
        }

        $view->arenas = Arenas::Combo();
        $view->detalhado = $data['detalhado'];

        return $view;
    }

    public function getFaturista()
    {
        $view = View('admin.bi.relatorios.faturista.index');

        $chart = Charts::multi('bar', 'highcharts')
            // Setup the chart settings
            ->title("My Cool Chart")
            // A dimension of 0 means it will take 100% of the space
            ->dimensions(0, 400)// Width x Height
            // This defines a preset of colors already done:)
            ->template("material")
            // You could always set them manually
            // ->colors(['#2196F3', '#F44336', '#FFC107'])
            // Setup the diferent datasets (this is a multi chart)
            ->dataset('Element 1', [5, 20, 100])
//            ->dataset('Element 2', [15,30,80])
//            ->dataset('Element 3', [25,10,40])
            // Setup what the values mean
            ->labels(['One', 'Two', 'Three']);

        return $view;
    }

    public function postFaturista()
    {
        $view = View('admin.bi.relatorios.faturista.relatorio');

        $params = Input::all();
        $ano = $params['ano'];
        $mes = $params['mes'];
        $arena = $params['arena'];
        $forma_faturamento = $params['forma_faturamento'];

        $view->ano = $ano;
        $view->mes = $mes;

        $ultimo_dia_mes = date("t", mktime(0, 0, 0, $mes, '01', $ano));
        $data_inicial = "{$ano}-{$mes}-01 00:00:00";
        $data_final = "{$ano}-{$mes}-{$ultimo_dia_mes} 23:59:59";

        $sql = Agendas::select(
            [
                'atendimento_procedimentos.faturista AS faturista',
                'arenas.nome as arena',
                \DB::raw('MONTH(atendimento_procedimentos.finalizacao) as mes'),
                \DB::raw('DAY(atendimento_procedimentos.finalizacao) as dia'),
                \DB::raw('COUNT(atendimento_procedimentos.id) as total'),
            ]
        )
            ->join('arenas', 'agendas.arena', '=', 'arenas.id')
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->whereBetween("atendimento_procedimentos.finalizacao", [$data_inicial, $data_final])
            ->whereIn('atendimento.status', [6, 8, 98, 99])
            ->orderBy('arena', 'ASC')
            ->orderBy('mes', 'ASC')
            ->orderBy('dia', 'ASC')
            ->groupBy('faturista', 'arena', 'mes', 'dia');

        if (!empty($arena)) {
            $sql->where('agendas.arena', $arena);
        }

        if (!empty($forma_faturamento)) {
            $sql->where('procedimentos.forma_faturamento', $forma_faturamento);
        }

        $_data = [];
        $_data_faturista = [];
        $fatutistas = Usuarios::getFaturistasCombo();
        foreach ($sql->get()->toArray() as $row) {
            $usuario = (array_key_exists($row['faturista'], $fatutistas)) ? $row['faturista'] : 0;

            $_data[$row['mes']][$row['arena']][$row['dia']][] = $row['total'];
            $_data_faturista[$row['mes']][$usuario][$row['dia']][] = $row['total'];
        }

        $view->relatorio = $_data;
        $view->relatorio_faturista = $_data_faturista;

        return $view;
    }

    public function postGridProcedimentos()
    {
        $view = View('admin.bi.relatorios.grid-procedimentos');

        $params = Input::all();
        $arena = Arenas::find($params['arena']);
        $view->mes = !empty($params['mes']) ? $params['mes'] : date('m');
        $view->ano = !empty($params['ano']) ? $params['ano'] : date('Y');
        $view->linha_cuidado = !empty($params['linha_cuidado']) ? $params['linha_cuidado'] : null;
        $view->finalizacao = !empty($params['finalizacao']) ? $params['finalizacao'] : null;
        $view->medico = !empty($params['medico']) ? $params['medico'] : null;

        $view->arena = $arena;

        return $view;
    }


    public function getApac()
    {
        $view = View('admin.relatorios.apac.index');

        return $view;
    }

    public function getApacFileDownload()
    {
        $file = './file/relatorio/apac/user-' . Auth::user()->id . '/APAC.TXT';

        $headers = array(
            'Content-Type: application/txt',
            'Content-Disposition:attachment; filename="{$file}"',
            'Content-Transfer-Encoding:binary',
            'Content-Length:' . filesize($file),
        );

        return \Response::download($file, time() . "APAC.TXT", $headers);
    }


    public function postApacFile()
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.apac.file');

        try {
            $path = 'file/relatorio/apac/user-' . Auth::user()->id . '/';
            $file = $path . "APAC.TXT";

            Util::RemoverPath($path);
            $_path = Util::RecursivePath($path);

            $view->file = false;

            $params = Input::all();
            $faturamento = Faturamento::find($params['faturamento']);

            $data['mes'] = Util::StrPadLeft($faturamento->mes, 2, 0);
            $data['ano'] = $faturamento->ano;
            $data['faturamento'] = $faturamento->id;
            $data['lote'] = $params['lote'];

            $file_content = (new \App\Http\Helpers\Exportacao\APACProducao)->Exportacao($data);

            if ($file_content) {
                $view->file = true;

                $view->link = $path . "APAC.TXT";
                file_put_contents($file, $file_content, FILE_APPEND);
            }
        } catch (\Exception $e) {
            print("<pre>LINE: " . __LINE__ . " - " . print_r($e->getFile(), 1) . "</pre>"); #debug-edersonsandre
            print("<pre>LINE: " . __LINE__ . " - " . print_r($e->getLine(), 1) . "</pre>"); #debug-edersonsandre
            exit("<pre>LINE: " . __LINE__ . " - " . print_r($e->getMessage(), 1) . "</pre>"); #debug-edersonsandre
        }

        return $view;
    }

    public function getBpa()
    {
        $view = View('admin.bi.relatorios.bpa');

        return $view;
    }

    public function postBpaFile()
    {
        set_time_limit(1200);
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.bi.relatorios.bpa-file');

        try {
            $view->file = false;

            $params = Input::all();
            $faturamento = Faturamento::find($params['faturamento']);

            $data['mes'] = Util::StrPadLeft($faturamento->mes, 2, 0);
            $data['ano'] = $faturamento->ano;
            $data['faturamento'] = $faturamento->id;
            $data['lote'] = $params['lote'];

            $file_content = BI::BoletimProducaoAmbulatorial($data);

            if ($file_content) {
                $view->file = true;

                $view->link = $file_content;
            }
        } catch (\Exception $e) {
            print("<pre>LINE: " . __LINE__ . " - " . print_r($e->getFile(), 1) . "</pre>"); #debug-edersonsandre
            print("<pre>LINE: " . __LINE__ . " - " . print_r($e->getLine(), 1) . "</pre>"); #debug-edersonsandre
            exit("<pre>LINE: " . __LINE__ . " - " . print_r($e->getMessage(), 1) . "</pre>"); #debug-edersonsandre
        }

        return $view;
    }

    public function getBpaFileDownload()
    {
        $file = './file/relatorio/bpa/user-' . Auth::user()->id . '/BPA.TXT';

        $headers = array(
            'Content-Type: application/txt',
            'Content-Disposition:attachment; filename="{$file}"',
            'Content-Transfer-Encoding:binary',
            'Content-Length:' . filesize($file),
        );

        return \Response::download($file, time() . "BPA.TXT", $headers);
    }

    public function getProducao()
    {
        $view = View('admin.bi.relatorios.producao');

        return $view;
    }

    public function postProducaoGrid(Request $request)
    {
        $view = View('admin.bi.relatorios.producao-grid');

        $params = Input::all();

        $view->arena = $request->get('arena');
        $view->linha_cuidado = $request->get('linha_cuidado');
        $view->date = $request->get('data');
        $view->medico = $request->get('medico');
        $view->digitador = $request->get('digitador');

        return $view;
    }

    public function getProducaoExportacao()
    {
        $view = View('admin.bi.relatorios.producao.exportacao.index');

        return $view;
    }

    public function postProducaoExportacaoGrid(Request $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';
        Upload::recursive_mkdir($path);

        $filename = "relatorio-producao";

        try {
            Excel::create($filename, function ($excel) use ($request) {

                $relatorio = Relatorios::RelatorioProducao2($request->get('data')['inicial'],
                    $request->get('data')['final'], $request->get('lote'));

                $excel->sheet("DATA", function ($sheet) use ($relatorio, $request) {
                    $sheet->loadView('admin.bi.relatorios.producao.exportacao.data')->with('relatorio',
                        $relatorio)->with('periodo', $request->get('data'));
                    $sheet->setAutoFilter('A1:G1');

                });

            })->store('xlsx', public_path($path));

        } catch (\Exception $e) {
            print("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getMessage(),
                    1) . "</pre>"); #debug-edersonsandre
            exit("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getLine(),
                    1) . "</pre>"); #debug-edersonsandre
        }

        $data['link'] = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        $data['download'] = '<div class="alert alert-success"><a href="' . $data['link'] . '" target="_blank" ><strong>Clique aqui</strong></a> para fazer download do arquivo em excel.</div>';

        return $data;
    }

    public function getProducaoData()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);

        $params = Input::all();

        switch (intval($params['detalhado'])) {
            case 0 :
                $view = View('admin.bi.relatorios.producao-data.data');
                break;
            case 1 :
                $view = View('admin.bi.relatorios.producao-data.data-detalhado');
                break;
            case 2 :
                $data = \App\Http\Helpers\Relatorios::RelatorioProducaoDetalhamentoPaciente3($params['date'], $params['arena'], $params['linha_cuidado'], $params['profissional'], $params['digitador']);

                $headers = array(
                    "Content-type" => "text/plain",
                    "Content-Disposition" => "attachment; filename=relatorio-producao.csv",
                    "Pragma" => "no-cache"
                );

                $columns = array('Prontuário', 'SUS', 'Nome', 'Idade', 'Sexo', 'Código SUS','Procedimento', 'Digitador');

                $callback = function() use ($data, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns, ";");

                    foreach($data as $row) {
                        fputcsv($file, (array) $row, ";");
                    }
                    fclose($file);
                };
                return \Response::stream($callback, 200, $headers);

                break;
        }

        $view->arena = $params['arena'];
        $view->linha_cuidado = $params['linha_cuidado'];
        $view->date = $params['date'];
        $view->digitador = $params['digitador'];
        $view->profissional = $params['profissional'];
        $view->detalhado = $params['detalhado'];

        if (in_array($params['detalhado'], [0, 1])) {
            $view->detalhado = 0;

            $options = new Options();

            $dompdf = new Dompdf($options);

            $view->canvas = $canvas = $dompdf->getCanvas();
            $contents = $view->render();

            $dompdf->loadHtml($contents);

            $dompdf->setPaper('A4');

            $dompdf->render();

            $dompdf->get_canvas()->page_text(532, 798, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));

            $nome_arquivo = 'relatorio-producao';
            $dompdf->stream($nome_arquivo, array("Attachment" => false));
            die;
        }

        if (in_array($params['detalhado'], [2])) {
            $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $filename = "relatorio-producao-excel";
            try {
                Excel::create($filename, function ($excel) use ($view) {

                    $excel->sheet("DATA", function ($sheet) use ($view) {
                        $sheet->loadView($view->getName())->with('params', $view->getData());
                        $sheet->setAutoFilter('A1:H1');
                        $sheet->setFreeze('A2');

                        $sheet->setColumnFormat(array(
                            'A' => '0',
                            'B' => '0',
                            'C' => PHPExcel_Style_NumberFormat::FORMAT_TEXT,
                            'E' => '0'
                        ));

                        $style = array(
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                                'wrap' => true
                            )
                        );

                        $sheet->getStyle('G')->applyFromArray($style);
                    });

                })->export('xlsx');

            } catch (\Exception $e) {
                print("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getMessage(),
                        1) . "</pre>"); #debug-edersonsandre
                exit("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getLine(),
                        1) . "</pre>"); #debug-edersonsandre
            }
        }
    }

    public function getTempo()
    {
        $view = View('admin.bi.relatorios.tempo.index');

        return $view;
    }

    public function postRelatorioTempo()
    {
        $view = View('admin.bi.relatorios.tempo.grid');
        $params = Input::all();

        $view->relatorio = AtendimentoTempo::getRelatorio($params);

        return $view;
    }


    public function getFaturamento()
    {
        $view = View('admin.bi.relatorios.faturamento.index');

        return $view;
    }

    public function postFaturamentoGrid()
    {
        $view = View('admin.bi.relatorios.faturamento.grid');
        $params = Input::all();

        $view->relatorio = Faturamento::Relatorio($params);

        return $view;
    }

    public function getPrevisaoFaturamento()
    {
        $view = View('admin.bi.relatorios.previsao-faturamento.index');

        return $view;
    }

    public function postPrevisaoFaturamento(PrevisaoFaturamentoRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $linha_cuidado = $request->get('linha_cuidado');
        $lote = $request->get('lote');
        $faturamento = $request->get('faturamento');

        try {
            $faturamento = Faturamento::find($faturamento);
            if (empty($linha_cuidado)) {
                $linhas_cuidado = LinhaCuidado::Combo();
            } else {
                $linhas_cuidado = LinhaCuidado::where('id', $linha_cuidado)->lists('nome', 'id')->toArray();
            }


            $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $filename = "previsao-faturamento-{$faturamento->ano}-{$faturamento->mes}";

            try {
                Excel::create($filename, function ($excel) use ($lote, $faturamento, $linhas_cuidado) {

                    $excel->sheet("ESPECIALIDADES", function ($sheet) use ($linhas_cuidado, $lote, $faturamento) {
                        $sheet->setFreeze('A1');

                        $sheet->loadView('admin.bi.relatorios.previsao-faturamento.data-especialidade')->with('linhas_cuidado',
                            $linhas_cuidado)->with('lote', $lote)->with('faturamento', $faturamento);
                    });

                    $excel->sheet("PROCEDIMENTOS", function ($sheet) use ($linhas_cuidado, $lote, $faturamento) {
                        $sheet->setColumnFormat(array(
                            'G' => '0%',
                            'J' => '0%',
                            'M' => '0%',
                            'N' => '0%',
                            'O' => '0%',
                        ));

                        $sheet->setFreeze('A1');
                        $sheet->setFreeze('A2');

                        $sheet->loadView('admin.bi.relatorios.previsao-faturamento.data')->with('linhas_cuidado',
                            $linhas_cuidado)->with('lote', $lote)->with('faturamento', $faturamento);
                    });

                })->store('xlsx', public_path($path));

            } catch (\Exception $e) {
                print("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getMessage(),
                        1) . "</pre>"); #debug-edersonsandre
                exit("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getLine(),
                        1) . "</pre>"); #debug-edersonsandre
            }

            $data['link'] = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
            $data['download'] = '<div class="alert alert-success"><a href="' . $data['link'] . '" target="_blank" ><strong>Clique aqui</strong></a> para fazer download do arquivo em excel.</div>';

            return $data;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }

        return $view;
    }

    public function getBiopsia()
    {
        $view = View('admin.bi.relatorios.biopsia.index');

        return $view;
    }

    public function postBiopsia()
    {
        $data = Input::all();

        $file = RelatorioExcel::get($data);

        return "<div class='alert alert-success align-center'><a href='{$file['download']}' id='btn-download-arquivo-biopsia' target='_blank'>CLIQUE AQUI PARA FAZER DOWNLOAD DO ARQUIVO!</a></div>";
    }

    public function getBiopsiaResumo()
    {
        $view = View('admin.bi.relatorios.biopsia.resumo');

        return $view;
    }

    public function postBiopsiaResumo()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);

        $view = View('admin.bi.relatorios.biopsia.resumo-data');

        $params = Input::all();

        $sql = AtendimentoLaudo::getLaudoData($params['arena'], $params['linha_cuidado'], $params['ano'],
            $params['mes']);
        $data = $sql->toArray();

        $_data = array();
        if (!empty($data)) {
            foreach ($data as $row) {
                $resultado = empty($row['resultado']) ? 1 : $row['resultado'];
                $_data[$row['arena_nome']][$row['linha_cuidado']][$resultado][] = 1;
            }
        }
        $view->_data = $_data;

        return $view;
    }

    public function getAbsenteismo()
    {
        return $this->_absenteismo();
    }

    public function postAbsenteismo()
    {

        try {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 300);

            $params = Input::all();
            if (empty($params['arena'])) {
                throw new \Exception("Selecione uma arena");
            }

            if (empty($params['tipo'])) {
                throw new \Exception("Selecione um tipo de relatorio");
            }

            $data = ($params['tipo'] == 1) ? $this->_absenteismo() : $this->_absenteismo_arena();

            return "<div class='alert alert-success align-center'><a href='{$data['download']}' id='btn-download-arquivo-absenteismo'  target='_blank'>CLIQUE AQUI PARA FAZER DOWNLOAD DO ARQUIVO!</a></div>";
        } catch (\Exception $e) {
            return "<div class='alert alert-danger'>{$e->getMessage()}</div>";
        }

    }

    protected function _absenteismo_arena()
    {
        $params = Input::all();

        $perido = explode("-", $params['periodo']);

        $mes = $perido[1];
        $ano = $perido[0];

        $filename = "{$ano}-{$mes}-absenteismo-arena";

        $arenas = Arenas::Combo();

        $path = PATH_FILE_RELATORIO . 'excel/absenteismo/' . Util::getUser() . '/';
        Upload::recursive_mkdir($path);

        Excel::create($filename, function ($excel) use ($arenas) {

            $i = 1;
            foreach ($arenas as $cod_arena => $arena) {

                $excel->sheet($arena, function ($sheet) use ($arena, $cod_arena) {
                    $sheet->loadView('relatorio.excel.absenteismo-by-arena')->with('arena', $arena)->with('cod_arena',
                        $cod_arena);
                });
                $i++;

                break;
            }


        })->store('xlsx', public_path($path));

        return [
            'download' => '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx',
        ];

    }

    protected function _absenteismo()
    {
        $params = Input::all();

        if (!empty($params)) {

            $perido = explode("-", $params['periodo']);

            $mes = $perido[1];
            $ano = $perido[0];

            $arena = Arenas::find($params['arena']);
            $nome_arena = str_replace("/", "-", snake_case(strtolower(Util::RemoveAcentos($arena->nome))));

            $path = PATH_FILE_RELATORIO . 'excel/absenteismo/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $filename = "{$ano}-{$mes}-absenteismo-{$nome_arena}";

            $dates = Util::datesMonth($mes, $ano);

            try {
                Excel::create($filename, function ($excel) use ($dates, $arena) {

                    $i = 1;
                    foreach ($dates as $semana => $dias) {

                        $excel->sheet("{$i}ª SEMANA", function ($sheet) use ($arena, $dias, $i) {
                            $sheet->loadView('relatorio.excel.absenteismo-by-linha_cuidado')->with('arena',
                                $arena)->with('dias', $dias);
                        });
                        $i++;
                    }


                })->store('xlsx', public_path($path));

                return [
                    'download' => '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx',
                ];

            } catch (\Exception $e) {
                print("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getMessage(),
                        1) . "</pre>"); #debug-edersonsandre

            }
        } else {
            $view = View('admin.bi.relatorios.absenteismo');

            return $view;
        }
    }

    public function getProcedimentoSobra()
    {

    }

    public function getRecepcaoTempo()
    {
        $view = View('admin.bi.relatorios.recepcao-tempo.index');

        return $view;
    }

    public function postRecepcaoTempo()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        $params = Input::all();

        try {

            if (!empty($params)) {
                $view = View('admin.bi.relatorios.recepcao-tempo.relatorio');

                $ano = $params['ano'];
                $mes = $params['mes'];
                $arena = $params['arena'];
                $export_excel = $params['exportar'];
                $linha_cuidado = $params['linha_cuidado'];

                $ultimo_dia_mes = date("t", mktime(0, 0, 0, $mes, '01', $ano));
                $data_inicial = "{$ano}-{$mes}-01 00:00:00";
                $data_final = "{$ano}-{$mes}-{$ultimo_dia_mes} 23:59:59";

                $sql = Atendimentos::select(
                    [
                        'arenas.nome AS arena_nome',
                        'linha_cuidado.nome AS linha_cuidado_nome',
                        DB::raw("DATE_FORMAT(atendimento_tempo.recepcao_in,'%Y-%m-%d %H') as periodo"),
                        DB::raw("count('atendimento_tempo.id') as total"),
                    ]
                )
                    ->join('atendimento_tempo', 'atendimento.id', '=', 'atendimento_tempo.atendimento')
                    ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
                    ->join('arenas', 'arenas.id', '=', 'agendas.arena')
                    ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
                    ->whereBetween('atendimento_tempo.recepcao_in', [$data_inicial, $data_final])
                    ->groupBy([
                        'arena_nome',
                        'linha_cuidado_nome',
                        'periodo'
                    ])//->limit(10)
                ;

                if (!empty($arena)) {
                    $sql->where('arenas.id', $arena);
                }

                if (!empty($linha_cuidado)) {
                    $sql->where('linha_cuidado.id', $linha_cuidado);
                }

                $res = $sql->get();

                $relatorio = $res;

                $_data = [];
                $__data_time = [];
                $__data_only = [];
                $__data_arena = [];
                $__data_linha_cuidado = [];
                if (count($res)) {
                    foreach ($res as $row) {
                        $date = explode(" ", $row->periodo);

                        $_data[$date[0]][$date[1]][] = $row->total;
                        $_data_only[$date[0]][] = $row->total;
                        $_data_time[$date[1]][] = $row->total;
                        $_data_arena[$row->arena_nome][] = $row->total;
                        $_data_linha_cuidado[$row->linha_cuidado_nome][] = $row->total;
                    }

                    foreach ($_data_only as $key => $row) {
                        $data = explode("-", $key);
                        $__data_only[$data[2] . "-" . $data[1]] = array_sum($row);
                    }

                    foreach ($_data_time as $key => $row) {
                        $__data_time[$key] = array_sum($row);
                    }

                    foreach ($_data_arena as $key => $row) {
                        $__data_arena[$key] = array_sum($row);
                    }

                    foreach ($_data_linha_cuidado as $key => $row) {
                        $__data_linha_cuidado[$key] = array_sum($row);
                    }
                }

                $view->time = $__data_time;
                $view->date = $__data_only;

                ksort($__data_time);
                $chart = Charts::create('line', 'highcharts')
                    ->title('Por hora')
                    ->elementLabel('Hora')
                    ->labels(array_keys($__data_time))
                    ->values(array_values($__data_time))
                    ->dimensions(1000, 500)
                    ->responsive(true);
                $view->chart_tempo = $chart;

                ksort($__data_only);
                $chart = Charts::create('line', 'highcharts')
                    ->title('Por dia')
                    ->elementLabel('Quantidade')
                    ->labels(array_keys($__data_only))
                    ->values(array_values($__data_only))
                    ->dimensions(1000, 500)
                    ->responsive(true);
                $view->chart_date = $chart;

                ksort($__data_arena);
                $chart = Charts::create('bar', 'highcharts')
                    ->title('Por arena')
                    ->elementLabel('Quantidade')
                    ->labels(array_keys($__data_arena))
                    ->values(array_values($__data_arena))
                    ->dimensions(1000, 500)
                    ->responsive(true);
                $view->chart_arena = $chart;

                ksort($__data_linha_cuidado);
                $chart = Charts::create('bar', 'highcharts')
                    ->title('Por linha de cuidado')
                    ->elementLabel('Quantidade')
                    ->labels(array_keys($__data_linha_cuidado))
                    ->values(array_values($__data_linha_cuidado))
                    ->dimensions(1000, 500)
                    ->responsive(true);
                $view->chart_linha_cuidado = $chart;

                $view->download = null;

                if ($export_excel == 1) {
                    $path = PATH_FILE_RELATORIO . 'excel/atendimento/' . Util::getUser() . '/';
                    Upload::recursive_mkdir($path);
                    $filename = "relatorio-tempo-recepcao";


                    Excel::create($filename,
                        function ($excel) use ($relatorio, $arena, $linha_cuidado, $data_inicial, $data_final) {

                            if (!empty($arena)) {

                                $sql = Atendimentos::select(
                                    [
                                        'atendimento.id AS atendimento_id',
                                        'atendimento.created_at AS atendimento_criacao',
                                        'agendas.id AS agendas_id',
                                        'agendas.data AS agendas_data',
                                        'arenas.nome AS arena_nome',
                                        'linha_cuidado.nome AS linha_cuidado_nome',
                                        'atendimento_tempo.recepcao_in',
                                        'atendimento_tempo.recepcao_out',
                                        'atendimento_tempo.medico_in',
                                        'atendimento_tempo.medico_out',
                                        'pacientes.nome',
                                        'pacientes.cpf',
                                        'pacientes.cns',
                                        'pacientes.sexo',
                                        'pacientes.nascimento',
                                        'pacientes.celular',
                                        'pacientes.cep',
                                        'pacientes.cidade',
                                        'estabelecimento.nome AS estabelecimento',
                                    ]
                                )
                                    ->join('atendimento_tempo', 'atendimento.id', '=', 'atendimento_tempo.atendimento')
                                    ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
                                    ->join('arenas', 'arenas.id', '=', 'agendas.arena')
                                    ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
                                    ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
                                    ->join('estabelecimento', 'estabelecimento.id', '=', 'pacientes.estabelecimento')
                                    ->whereBetween('atendimento_tempo.recepcao_in', [$data_inicial, $data_final]);

                                if (!empty($arena)) {
                                    $sql->where('arenas.id', $arena);
                                }

                                if (!empty($linha_cuidado)) {
                                    $sql->where('linha_cuidado.id', $linha_cuidado);
                                }

                                $_relatorio = $sql->get();

                                $excel->sheet("Atendimentos", function ($sheet) use ($_relatorio) {
                                    $sheet->loadView('relatorio.excel.relatorio_tempo.atendimentos')->with('relatorio',
                                        $_relatorio);

                                    // $sheet->setAutoFilter('A2:D1');
                                });
                            }

                            $excel->sheet("Relatório Abertura Atendimento", function ($sheet) use ($relatorio) {
                                $sheet->loadView('relatorio.excel.relatorio_tempo.abertura')->with('relatorio',
                                    $relatorio);

                                $sheet->setAutoFilter('A1:D1');
                            });
                        })->store('xlsx', public_path($path));

                    $view->download = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
                }

                return $view;
            }
        } catch (\Exception $e) {
            print("<pre>LINE: " . __LINE__ . " - Exception: " . print_r($e->getMessage(),
                    1) . "</pre>"); #debug-edersonsandre
        }

    }

    public function getFaturamentoGordura()
    {
        $view = View('admin.bi.relatorios.faturamento.gordura.index');

        return $view;

    }

    public function postFaturamentoGordura(RelatorioGorduraFaturamentoRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.bi.relatorios.faturamento.gordura.relatorio');

        $faturamento = $request->get('faturamento');


        try {

            $data = DB::table('tmp_faturamento_gordura')->select(
                [
                    'data',
                    'faturamento',
                    'arena',
                    'linha_cuidado',
                    'procedimento_sus',
                    'procedimento',
                    'faturado',
                    'crm',
                    'medico',
                    'quantidade',
                ]
            )
                ->where('faturado', "=", 0)
                ->orWhere('faturamento', $faturamento)->get();

            $headers = ['DATA', 'FATURAMENTO', 'UNIDADE', 'ESPECIALIDADE', 'CODIGO PROCEDIMENTO', 'NOME PROCEDIMENTO', 'FATURADO', 'CRM MEDICO', 'NOME MEDICO', 'QUANTIDADE'];
            $lines[] = implode(";", $headers);
            foreach ($data as $row) {
                $lines[] = implode(";", (array)$row);
            }

            $path = PATH_FILE_RELATORIO . 'excel/faturamentos/gordura/';
            Upload::recursive_mkdir($path);
            $filename = "relatorio-faturamento-gordura.csv";
            file_put_contents($path . $filename, implode("\r\n", $lines));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;
        } catch (\Exception $e) {
            exit("<pre>" . print_r($e->getMessage(), true) . "</pre>");
            $view->link = null;
        }

        return $view;
    }

    public function postFaturamentoGorduraVisualizacaoMes()
    {
        $view = View('admin.bi.relatorios.faturamento.gordura.visualizacao-mes');
        $params = Input::all();

        $date = explode("-", $params['mes']);
        $ultimo_dia_mes = date("t", mktime(0, 0, 0, $date[1], '01', $date[0]));

        $data_inicial = $params['mes'] . "-01 00:00:00";
        $data_final = $params['mes'] . "-{$ultimo_dia_mes} 23:59:59";

        $sql = Atendimentos::select(
            [
                "arenas.nome as arena",
                "linha_cuidado.nome as linha_cuidado",
                DB::raw("DATE_FORMAT(agendas.data,'%Y-%m-%d') as data"),
                "agendas.status as status",
                "agendas.id as agenda_id",
                "pacientes.nome AS paciente",
                DB::raw("sum(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) as total"),
            ]
        )
            ->join('atendimento_procedimentos', function ($join) {
                $join->on('atendimento.id', '=', 'atendimento_procedimentos.atendimento')
                    ->where('atendimento_procedimentos.faturado', '=', 0);
            })
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->where('atendimento_procedimentos.procedimento', $params['procedimento'])
            ->where('atendimento_procedimentos.faturado', false)
            ->where('agendas.data', ">=", DATE_INICIO_SISTEMA)
            ->whereBetween("agendas.data", [$data_inicial, $data_final])
            ->whereIn('agendas.status', array(6))
            ->groupBy([
                DB::raw("YEAR(agendas.data)"),
                DB::raw("MONTH(agendas.data)"),
                DB::raw("DAY(agendas.data)"),
                'arena',
                'linha_cuidado',
                'agenda_id',
                'status',
                'paciente',
            ])
            ->orderBy('arena', 'asc')
            ->orderBy('linha_cuidado', 'asc')
            ->orderBy('paciente', 'asc');

        if (!empty($params['arena'])) {
            $sql->where('arenas.id', $params['arena']);
        }

        if (!empty($params['linha_cuidado'])) {
            $sql->where('linha_cuidado.id', $params['linha_cuidado']);
        }

        $data = $sql->get();

        $relatorio = [];
        if (!empty($data)) {
            foreach ($data as $row) {
                $relatorio[$row->status][] = $row;
            }
        }

        $view->relatorio = $relatorio;

        return $view;
    }

    public function getFaturamentoLoteGerencia()
    {
        $view = View('admin.bi.relatorios.linha-cuidado.metrica');

        if (!$this->validaRelatorioGerencia()) {
            return redirect("/");
        }

        return $view;
    }

    public function postFaturamentoLoteGerencia(Request $request)
    {
        $view = View('admin.bi.relatorios.linha-cuidado.grid-metrica');

        $lote = Lotes::whereIn('id', $request->get('lote'))->get();

        $_status[] = 98;
        $_status[] = 99;
        $_status[] = 6;
        $_status[] = 10;

        if (count($lote)) {
            $arenas = Lotes::getArenas($lote->lists('id')->toArray());

            $date = Carbon::create($request->get('ano'), $request->get('mes'), '01', '0', '0', '0');
            $start = $date->toDateTimeString();
            $end = $date->lastOfMonth()->toDateTimeString();

            $_data = LinhaCuidado::distinct()->select([
                'linha_cuidado.nome AS linha_cuidado',
                'procedimentos.nome AS procedimento',
                DB::raw('count(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) AS total')
            ])
                ->join('agendas', 'agendas.linha_cuidado', '=', 'linha_cuidado.id')
                ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
                ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
                ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
                ->whereIn('agendas.arena', $arenas)
                ->whereIn('atendimento.status', $_status)
                ->whereBetween('agendas.data', [$start, $end])
                ->groupBy('linha_cuidado')
                ->groupBy('procedimento')
                ->orderBy('linha_cuidado', 'asc')
                ->orderBy('procedimento', 'asc')
                ->get();

            $data = [];
            if (count($_data)) {
                foreach ($_data as $row) {
                    $data[$row->linha_cuidado][$row->procedimento] =
                        [
                            'procedimento' => $row->procedimento,
                            'total' => $row->total
                        ];

                    $procedimentos[$row->procedimento][] = $row->total;
                }

                ksort($procedimentos);
            }

            $view->erro = null;
            if (empty($procedimentos)) {
                $view->erro = "Não foi encontrado procedimentos executados para este lote";
            } else {
                $view->report = $data;
                $view->report_procedimentos = $procedimentos;
            }
        }
        return $view;
    }

    public function postFaturamentoLoteGerenciaXls(Request $request)
    {
        $return['success'] = false;

        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $lotes = Lotes::whereIn('id', $request->get('lotes'))->get();

        $_status[] = 98;
        $_status[] = 99;
        $_status[] = 6;
        $_status[] = 10;

        if (count($lotes)) {

            $date = Carbon::create($request->get('ano'), $request->get('mes'), '01', '0', '0', '0');
            $start = $date->toDateTimeString();
            $end = $date->lastOfMonth()->toDateTimeString();

            $path = PATH_FILE_RELATORIO . 'excel/gerencia/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);
            $filename = "relatorio-procedimentos-metas";

            try {
                Excel::create($filename, function ($excel) use ($lotes, $start, $end, $_status) {
                    $contrato = 2;
                    $i = 1;
                    foreach ($lotes as $lote) {
                        $arenas = Lotes::getArenas([$lote->id]);

                        $_data = LinhaCuidado::distinct()->select([
                            'procedimentos.id AS procedimento_id',
                            'procedimentos.nome AS procedimento_nome',
                            'procedimentos.sus AS procedimento_sus',
                            DB::raw('count(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) AS total')
                        ])
                            ->join('agendas', 'agendas.linha_cuidado', '=', 'linha_cuidado.id')
                            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
                            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=',
                                'atendimento.id')
                            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
                            ->whereIn('agendas.arena', $arenas)
                            ->whereBetween('agendas.data', [$start, $end])
                            ->groupBy('procedimentos.id')
                            ->groupBy('procedimentos.nome')
                            ->whereIn('atendimento.status', $_status)
                            ->groupBy('procedimentos.sus')
                            ->orderBy('procedimentos.nome', 'asc')
                            ->get();

                        $excel->sheet($lote->nome, function ($sheet) use ($lote, $_data, $contrato) {
                            $sheet->loadView('relatorio.excel.faturamento.procedimento-valores')->with('lote',
                                $lote)->with('relatorio', $_data)->with('contrato', $contrato);

                            $sheet->setColumnFormat(array(
                                'C' => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                                'F' => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
                                'E' => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                                'F' => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
                                'G' => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                                'J' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                                'K' => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                                'L' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                            ));

                            //$sheet->protect(123);
                        });


                        $i++;
                    }


                })->store('xlsx', public_path($path));

                $return['link'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
                $return['success'] = true;
            } catch (\Exception $e) {
                //print("<pre`>LINE: " . __LINE__ . " - Exception: " . print_r($e->getMessage(), 1) . "</pre>"); #debug-edersonsandre
            }
        }

        return $return;
    }

    public function getIndicadoresProducao()
    {
        $view = View('admin.bi.relatorios.indicadores.producao');

        return $view;
    }

    public function postIndicadoresProducao(Request $request)
    {
        $view = View('admin.bi.relatorios.indicadores.producao-data');
        $view->error = null;

        try {
            $view->relatorio = Relatorios::IndicacoresProducao($request);
        } catch (\Exception $e) {
            $view->error = $e->getMessage();
        }

        return $view;
    }

    public function getPacientesDias()
    {
        $view = View('admin.bi.relatorios-gerenciais.pacientes-dias.index');

        return $view;
    }

    public function postPacientesDias(Request $request)
    {
        $return['success'] = false;

        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $_data = Relatorios::PacientesDia($request->get('ano'), $request->get('mes'), $request->get('arena'),
            $request->get('medico'));

        $path = PATH_FILE_RELATORIO . 'excel/relatorio-gerenccia/pacientes-dia/' . Util::getUser() . '/';
        Upload::recursive_mkdir($path);
        $filename = "relatorio-pacientes-dias";

        try {
            Excel::create($filename, function ($excel) use ($_data) {
                $excel->sheet("PACIENTES - DIAS", function ($sheet) use ($_data) {
                    $sheet->loadView('relatorio.excel.relatorio-gerenciais.pacientes-dias')->with('relatorio', $_data);
                });
            })->store('xlsx', public_path($path));

            $return['link'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
            $return['success'] = true;
        } catch (\Exception $e) {
            //print("<pre`>LINE: " . __LINE__ . " - Exception: " . print_r($e->getMessage(), 1) . "</pre>"); #debug-edersonsandre
        }

        return $return;
    }

}

