<?php

namespace App\Http\Controllers\Admin\Relatorios;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Relatorios\GorduraHelpers;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Http\Requests\Admin\Relatorios\GorduraDetalhadoRequest;
use App\Procedimentos;
use Illuminate\Http\Request;

class GorduraDetalhadoController extends Controller
{

    public function getIndex()
    {
        $view = View('admin.relatorios.gordura-detalhado.index');

        return $view;
    }

    public function postIndex(GorduraDetalhadoRequest $request)
    {
        $view = View('admin.relatorios.gordura-detalhado.data');
        $view->contrato = $request->get('contrato');

        try {
            $data = GorduraHelpers::getGordura($request->get('contrato'), $request->get('status'));
            $status = Util::StatusAgenda();
            if (empty($data[0])) {
                throw new \Exception("Sem registros!");
            }

            $headers = ['UNIDADE', 'ESPECIALIDADE', 'STATUS', 'DATA',  'SUS', 'PROCEDIMENTO', 'FINALIZAÇÃO', 'TOTAL'];
            $lines[] = implode(";", $headers);
            foreach ($data->toArray() as $row) {
                $row['status']  = !empty($status[$row['status']]) ? $status[$row['status']] : $row['status'];

                $lines[] = implode(";", $row);
            }

            $path = PATH_FILE_RELATORIO . 'csv/gordura/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);
            $filename = "relatorio-gordura.csv";
            file_put_contents(public_path($path . $filename), implode("\r\n", $lines));

            $link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;

            $html = '<div class="alert alert-success text-left"><a href="' . $link . '" target="_blank" ><strong>Clique aqui</strong></a> para fazer download do arquivo.</div>';
        } catch (\Exception $e) {
            $html = "<div class='alert alert-danger text-left'>Não foi possível gerar o relatótio.<br />" . $e->getMessage() . "</div>";
            $view->link = null;
        }

        return $html;
    }


}