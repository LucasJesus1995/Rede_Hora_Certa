<?php

namespace App\Http\Controllers\Admin\Relatorios;

use App\Atendimentos;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Http\Requests\Admin\Relatorios\PacientesFaltasRequest;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Style_NumberFormat;

class PacientesFaltasController extends Controller
{

    public function getIndex()
    {
        $view = View('admin.relatorios.pacientes.faltas.index');

        return $view;
    }

    public function postIndex(PacientesFaltasRequest $request)
    {
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '-1');

        $view = View('admin.relatorios.pacientes.faltas.data');

        $date = Util::periodoMesPorAnoMes($request->get('ano'), $request->get('mes'));

        try {
            $data = Atendimentos::getPacientesFaltas($date, $request->get('arena'));
            if (empty($data)) {
                throw new \Exception("Sem dados");
            }

            $_classificacao = Util::getTipoAtendimento();

            $headers = ['Agenda', 'Data', 'Tipo de Atendimento', 'Unidade', 'Especialidade', 'Nome Paciente', 'SUS', 'Celular', 'Telefone (Comercial)', 'Telefone (Residencial)', 'Telefone (Contato)', 'Email'];
            $lines[] = implode(";", $headers);
            foreach ($data->toArray() as $row) {
                $row['tipo_atendimento'] = !empty($row['tipo_atendimento']) && array_key_exists($row['tipo_atendimento'], $_classificacao) ? $_classificacao[$row['tipo_atendimento']] : null;
                $lines[] = implode(";", $row);
            }

            $path = PATH_FILE_RELATORIO . 'excel/relatorios/pacientes-faltas/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);
            $filename = "relatorio-pacientes-faltas.csv";
            file_put_contents(public_path($path . $filename), implode("\r\n", $lines));

            $view->link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;
        } catch (\Exception $e) {
            exit("<pre>" . print_r($e->getMessage(), true) . "</pre>");
            $view->link = null;
        }

        return $view;
    }


}