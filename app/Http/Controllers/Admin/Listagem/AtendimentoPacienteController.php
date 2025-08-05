<?php

namespace App\Http\Controllers\Admin\Listagem;

use App\Atendimentos;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Http\Requests\Admin\Listagem\AtendimentoPacienteRequest;
use Maatwebsite\Excel\Facades\Excel;

class AtendimentoPacienteController extends Controller
{

    public function getIndex()
    {
        $view = View('admin.listagem.atendimento-paciente.index');

        return $view;
    }

    public function postIndex(AtendimentoPacienteRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.listagem.atendimento-paciente.data');

        $date = Util::Date2DB($request->get('data'));
        $arena = $request->get('arena');
        $linha_cuidado = $request->get('linha_cuidado');

        $atendimentos = Atendimentos::getAtendimentosPacientes($date, $arena, $linha_cuidado);

        try {
            $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $filename = $date."___atendimentos-procedimentos";

            if (count($atendimentos) > 0) {

                try {
                    Excel::create($filename, function ($excel) use ($atendimentos) {

                        $excel->sheet("ATENDIMENTOS", function ($sheet) use ($atendimentos) {
                            $sheet->loadView('admin.listagem.atendimento-paciente.data-excel')->with('atendimentos', $atendimentos);
                            $sheet->setColumnFormat(array(
                                'F' => 'dd/mm/yyyy',
                                'G' => 'hh:mm'
                            ));
                            $sheet->setAutoFilter('A1:J1');
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

}