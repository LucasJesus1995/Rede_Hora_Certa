<?php
namespace App\Http\Controllers\Admin\Relatorios;

use App\Http\Controllers\Controller;
use App\Http\Helpers\DateHelpers;
use App\Http\Helpers\Relatorios;
use App\Http\Helpers\Relatorios\GorduraHelpers;
use App\Http\Helpers\Util;
use App\Http\Requests\Admin\Relatorios\GorduraDetalhadoRequest;
use App\Http\Requests\Relatorio\PacientesAtendimentoRequest;
use App\Procedimentos;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PacientesAtendimentoController  extends Controller{

    public function getIndex(){
        $view = View('admin.relatorios.pacientes-atendidos.index');

        return $view;
    }

    public function postIndex(PacientesAtendimentoRequest $request){

        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.pacientes-atendidos.data');

        $data = Relatorios::getPacienteAtendidosPeriodo( Util::Date2DB($request->get('periodo_inicial')), Util::Date2DB($request->get('periodo_final')));

        try {
            if (empty($data[0])) {
                throw new \Exception("Sem dados");
            }

            $path = PATH_FILE_RELATORIO . 'excel/relatorios/pacientes-atendidos/' . Util::getUser() . '/';
            $filename = "relatorio-pacintes-atendidos";

            Excel::create($filename, function ($excel) use ($data, $request) {

                $excel->sheet("PACIENTES ATENDIDOS", function ($sheet) use ($data, $request) {
                    $sheet->loadView('admin.relatorios.pacientes-atendidos.data-excel')->with('relatorio', $data);

                    $sheet->setColumnFormat(array(
                        'D' => '00'
                    ));

                    $sheet->setAutoFilter('A1:E1');
                    $sheet->setFreeze('A2');

                });

            })->store('xlsx', public_path($path));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            $view->link = null;
        }

        return $view;
    }


}