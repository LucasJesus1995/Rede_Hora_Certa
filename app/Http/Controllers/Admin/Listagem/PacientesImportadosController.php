<?php

namespace App\Http\Controllers\Admin\Listagem;

use App\Agendas;
use App\Atendimentos;
use App\Faturamento;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Http\Requests\Admin\Listagem\AtendimentoPacienteRequest;
use App\Http\Requests\Relatorio\PacientesImportadosRequest;
use App\Http\Requests\Request;
use App\Pacientes;
use Maatwebsite\Excel\Facades\Excel;

class PacientesImportadosController extends Controller
{

    public function getIndex()
    {
        $view = View('admin.listagem.pacientes.importados.index');

        return $view;
    }

    public function postIndex(PacientesImportadosRequest $request)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');

        $view = View('admin.listagem.atendimento-paciente.data');

        $date = Util::Date2DB($request->get('data'));
        $arena = $request->get('arena');
        $linha_cuidado = $request->get('linha_cuidado');

        try {
            $data = Agendas::pacientesImportados($date, $arena, $linha_cuidado);

            if(empty($data[0])){
               throw new \Exception("Sem registros!");
            }

            $headers = ['ESTABELECIMENTO', 'DATA', 'HORÁRIO', 'PACIENTE', 'IDADE', 'SUS', 'CELULAR', 'TELEFONE COMERCIAL', 'TELEFONE RESIDENCIAL', 'TELEFONE CONTANTO', 'ESPECIALIDADE', 'UBS'];
            $lines[] = implode(";", $headers);
            foreach ($data->toArray() as $row) {
                $lines[] = implode(";", $row);
            }

            $path = PATH_FILE_RELATORIO . 'excel/pacientes/importados/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);
            $filename = "pacientes-importados.csv";
            file_put_contents(public_path($path . $filename), implode("\r\n", $lines));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;
        } catch (\Exception $e) {
            $view->link = null;
        }


        return $view;
    }

}