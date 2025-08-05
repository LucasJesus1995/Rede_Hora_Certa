<?php

namespace App\Http\Controllers\Admin\Relatorios;

use App\Agendas;
use App\Arenas;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Importacao\ImportacaoAgendasHelpers;
use App\Http\Helpers\Relatorios;
use App\Http\Helpers\Util;
use App\Http\Requests\Admin\Relatorios\ImportacaoAgendasRequest;
use App\Http\Requests\Relatorio\PacientesAtendimentoRequest;
use App\ImportacaoAgenda;
use App\LinhaCuidado;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportacaoAgendasController extends Controller
{

    public function getIndex()
    {
        $view = View('admin.relatorios.importacao-agendas.index');


        return $view;
    }

    public function postIndex(ImportacaoAgendasRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.pacientes-atendidos.data');

        $data = (new \App\Http\Helpers\Importacao\ImportacaoAgendasHelpers)->getAgendamentos($request->get('data'), $request->get('arena'));

        try {
            if (empty($data)) {
                throw new \Exception("Sem dados");
            }

            $path = PATH_FILE_RELATORIO . 'excel/relatorios/importacao-agendas/' . Util::getUser() . '/';
            $filename = "relatorio-importacao-agendas";

            Excel::create($filename, function ($excel) use ($data, $request) {

                $excel->sheet("IMPORTACOES", function ($sheet) use ($data, $request) {
                    $sheet->loadView('admin.relatorios.importacao-agendas.data-excel')->with('relatorio', $data);

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