<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 07/02/19
 * Time: 11:38
 */

namespace App\Http\Helpers;


use App\Pacientes;

class StringHelpers
{

    public static function pipeTDFichaAtendimento($letter, $limit)
    {
        $data = [];

        $letters = str_split(substr(str_pad($letter, $limit, " ", STR_PAD_RIGHT), 0, $limit));
        $c = 0;

        $data[] = "<table style='width: 100%; border-spacing: 5px; padding: 0; margin: 0px' >";
        $data[] = "<tr>";
        $size = 100 / $limit;
        foreach ($letters AS $l) {
            $style = ($c != count($letters) - 1) ? "border-right: 1px solid #000;" : "";


            $data[] = "<td style='padding-bottom: 3px !important; {$style} text-align: center; width: {$size}% '>";
            if (strlen(trim($l)) == 0) {
                $data[] = "&nbsp;";
            } else {
                $data[] = $l;
            }
            $data[] = "</td>";
            $c++;
        }
        $data[] = "</tr>";
        $data[] = "</table>";

        return implode("", $data);
    }

    public static function getEnderecoPaciente(Pacientes $paciente)
    {
        $data = [];
        if (!empty($paciente->endereco)) {
            $data[] = $paciente->endereco;

            if (!empty($paciente->numero)) {
                $data[] = ", {$paciente->numero}";
            }

            if (!empty($paciente->bairro)) {
                $data[] = " - {$paciente->bairro}";
            }
        }

        return implode("", $data);
    }

}