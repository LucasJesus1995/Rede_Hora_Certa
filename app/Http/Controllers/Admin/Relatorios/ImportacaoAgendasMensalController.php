<?php

namespace App\Http\Controllers\Admin\Relatorios;

use App\Agendas;
use App\Arenas;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Importacao\ImportacaoAgendasHelpers;
use App\Http\Helpers\Relatorios;
use App\Http\Helpers\Util;
use App\Http\Requests\Admin\Relatorios\ImportacaoAgendasMensalRequest;
use App\Http\Requests\Admin\Relatorios\ImportacaoAgendasRequest;
use App\Http\Requests\Relatorio\PacientesAtendimentoRequest;
use App\ImportacaoAgenda;
use App\LinhaCuidado;
use App\TempImportacaoAgenda;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportacaoAgendasMensalController extends Controller
{

    public function getIndex()
    {
        $view = View('admin.relatorios.importacao-agendas-mensal.index');

        return $view;
    }

    public function postIndex(ImportacaoAgendasMensalRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $date = explode("-", $request->get('periodo'));
        $periodo = Util::periodoMesPorAnoMes($date[0], $date[1]);

        $view = View('admin.relatorios.importacao-agendas-mensal.data');

        $data = TempImportacaoAgenda::whereBetween('date_importacao', [$periodo['start'], $periodo['end']])->get();

        try {
            if (empty($data)) {
                throw new \Exception("Sem dados");
            }

            $path = PATH_FILE_RELATORIO . 'excel/relatorios/importacao-agendas-mensal/' . Util::getUser() . '/';
            $filename = "relatorios.importacao-agendas-mensal";

            Excel::create($filename, function ($excel) use ($data, $request) {

                $excel->sheet("IMPORTACOES", function ($sheet) use ($data, $request) {
                    $sheet->loadView('admin.relatorios.importacao-agendas-mensal.data-excel')->with('relatorio', $data);

                    $sheet->setColumnFormat(array(
                        'F' => '000',
                        'A' => 'dd/mm/yyyy hh:mm:ss',
                        'B' => 'dd/mm/yyyy'
                    ));

                    $sheet->setAutoFilter('A1:I1');
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


}