<?php

namespace App\Http\Helpers\Biopsia;


use App\AtendimentoLaudo;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioExcel
{
    protected static function meses($key = null){
        $meses =  array(
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        );

        if($key){
            foreach ($meses AS $_key => $mes){
                if($_key == $key) {
                    return [$_key => $mes];
                }
            }
        }

        return $meses;
    }

    public static function get($params){
        set_time_limit(0);

        $meses = self::meses($params['mes']);
        $ano = $params['ano'];

        $path = PATH_FILE_RELATORIO.'excel/'.Util::getUser().'/';
        Upload::recursive_mkdir($path);

        $filename = "relatorio-biopsia-".$ano.$params['mes'];

        try {
            Excel::create($filename, function ($excel) use ($ano, $meses, $params) {
            foreach ($meses as $mes_key => $mes){
                $sql = AtendimentoLaudo::getLaudoData($params['arena'], $params['linha_cuidado'], $ano, $mes_key, $params['medico'], array(3));

                $data = self::mountDataLayout($sql->toArray());

                $excel->sheet($mes, function ($sheet) use ($data) {
                    $sheet->loadView('relatorio.excel.biopsia')->with('relatorio', $data);
                });
            }
            })->store('xlsx', public_path($path));
        }catch (\Exception $e){

        }

        return ['download' => '//'.$_SERVER['SERVER_NAME'].'/'.$path.$filename.'.xlsx'];
    }

    protected static function mountDataLayout($data = null)
    {
        $_data = array();
        if(!empty($data)){
            foreach ($data AS $row){
                $_data[Util::DBTimestamp2UserDate3($row['data'])][] =  $row;
            }

            ksort($_data);
        }

        return $_data;
    }

}