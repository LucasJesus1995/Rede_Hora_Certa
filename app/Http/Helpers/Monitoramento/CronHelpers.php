<?php


namespace App\Http\Helpers\Monitoramento;


class CronHelpers
{

    public static function getCronForceRun($index = null)
    {
        $data['faturamento'] = [
            'name' => 'Processamento do faturamento',
            'service' => 'cies:faturamento'
        ];

        if (!is_null($index) && in_array($index, array_keys($data))) {
            $data = $data[$index];
        }

        return $data;
    }



}