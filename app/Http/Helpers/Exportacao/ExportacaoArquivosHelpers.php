<?php

namespace App\Http\Helpers\Exportacao;

use App\Arenas;
use App\Http\Helpers\Util;
use App\LinhaCuidado;
use App\Procedimentos;
use App\Usuarios;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Style_NumberFormat;

class ExportacaoArquivosHelpers extends ExportacaoProducao
{
    public static function getFile($key)
    {
        $data = null;
        switch ($key) {
            case 'arenas' :
                $data = self::getArenas();
                break;
            case 'especialidades' :
                $data = self::getEspecialidades();
                break;
            case 'usuarios' :
                $data = self::getUsuariosSistema();
                break;
            case 'procedimentos' :
                $data = self::getProcedimentos();
                break;
        }

        return $data;
    }

    private static function getProcedimentos()
    {
        $file = null;
        $filename = 'procedimentos';
        $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';

        $data = Procedimentos::getProcedimentos();

        try {
            Excel::create($filename, function ($excel) use ($data) {

                $excel->sheet("PROCEDIMENTOS", function ($sheet) use ($data) {
                    $sheet->loadView('admin.exportacao.arquivos.procedimentos.data-excel')->with('data', $data);
                    $sheet->setAutoFilter('A1:W1');
                    $sheet->setFreeze('A2');
                });

            })->store('xlsx', public_path($path));

            $file = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $file;
    }

    private static function getUsuariosSistema()
    {
        $file = null;
        $filename = 'usuarios-sistema';
        $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';

        $data = Usuarios::getUsers();

        try {
            Excel::create($filename, function ($excel) use ($data) {

                $excel->sheet("USUARIOS", function ($sheet) use ($data) {
                    $sheet->loadView('admin.exportacao.arquivos.usuarios-sistema.data-excel')->with('data', $data);
                    $sheet->setAutoFilter('A1:G1');
                    $sheet->setFreeze('A2');
                });

            })->store('xlsx', public_path($path));

            $file = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $file;
    }

    private static function getEspecialidades()
    {
        $file = null;
        $filename = 'especialidades';
        $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';

        $data = LinhaCuidado::all();

        try {
            Excel::create($filename, function ($excel) use ($data) {

                $excel->sheet("ESPECIALIDADES", function ($sheet) use ($data) {
                    $sheet->loadView('admin.exportacao.arquivos.especialidades.data-excel')->with('data', $data);
                    $sheet->setAutoFilter('A1:G1');
                    $sheet->setFreeze('A2');
                });

            })->store('xlsx', public_path($path));

            $file = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $file;
    }

    private static function getArenas()
    {
        $file = null;
        $filename = 'arenas';
        $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';

        $data = Arenas::all();

        try {
            Excel::create($filename, function ($excel) use ($data) {

                $excel->sheet("UNIDADES", function ($sheet) use ($data) {
                    $sheet->loadView('admin.exportacao.arquivos.arenas.data-excel')->with('data', $data);
                    $sheet->setAutoFilter('A1:Q1');
                    $sheet->setFreeze('A2');
                });

                $data = Arenas::select([
                    'arenas.nome',
                    'arena_equipamentos.nome AS equipamento',
                    'arena_equipamentos.ativo',
                    'arena_equipamentos.created_at',
                    'arena_equipamentos.updated_at'
                ])
                    ->join('arena_equipamentos', 'arena_equipamentos.arena', '=', 'arenas.id')
                    ->get();

                $excel->sheet("EQUIPAMENTOS", function ($sheet) use ($data) {
                    $sheet->loadView('admin.exportacao.arquivos.arenas.data-excel-equipamentos')->with('data', $data);
                    $sheet->setAutoFilter('A1:E1');
                    $sheet->setFreeze('A2');
                });

            })->store('xlsx', public_path($path));

            $file = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $file;
    }

    public static function getAbsenteismoPerdaPrimariaAgenda($data)
    {
        $file = null;
        $filename = 'oferta-producao';
        $path = PATH_FILE_RELATORIO . 'excel/' . Util::getUser() . '/';

        try {
            Excel::create($filename, function ($excel) use ($data) {

                $excel->sheet("DATA", function ($sheet) use ($data) {
                    $sheet->loadView('admin.exportacao.arquivos.oferta-producao.data-excel')->with('data', $data);
                    $sheet->setAutoFilter('A1:L1');
                    $sheet->setFreeze('A2');

                    $sheet->setColumnFormat(array(
                        'M' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                        'N' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                        'O' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
                    ));
                });

            })->store('xlsx', public_path($path));

            $file = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $file;
    }


}