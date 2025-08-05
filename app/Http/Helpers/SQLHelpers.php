<?php

namespace App\Http\Helpers;


class SQLHelpers
{

    public static function getOfertasStatus()
    {
        $data[] = "CASE";
        foreach (DataHelpers::getOfertaStatus() AS $k => $label):
            $data[] = " WHEN ofertas.status = {$k} THEN '{$label}'";
        endforeach;
        $data[] = "ELSE ofertas.status";
        $data[] = "END as status";

        return implode("\n", $data);
    }

    public static function getOfertasPeriodo()
    {
        $data[] = "CASE";
        foreach (DataHelpers::getPeriodo() AS $k => $label):
            $data[] = " WHEN ofertas.periodo = {$k} THEN '{$label}'";
        endforeach;
        $data[] = "ELSE ofertas.periodo";
        $data[] = "END as periodo";

        return implode("\n", $data);
    }

    public static function getOfertasClassificacao()
    {
        $data[] = "CASE";
        $data[] = " WHEN ofertas.classificacao = 0 THEN ''";
        foreach (DataHelpers::getClassificacaoKeys() AS $k => $label):
            $data[] = " WHEN ofertas.classificacao = {$k} THEN '{$label}'";
        endforeach;
        $data[] = "ELSE ofertas.classificacao";
        $data[] = "END as classificacao";

        return implode("\n", $data);
    }

    public static function getOfertasSemana()
    {
        $data[] = "CASE";
        foreach (Util::diaSemanaAbreviado() AS $k => $label):
            $data[] = " WHEN (DAYOFWEEK(ofertas.data) - 1) = {$k} THEN '{$label}'";
        endforeach;
        $data[] = "ELSE (DAYOFWEEK(ofertas.data) - 1)";
        $data[] = "END as semana";

        return implode("\n", $data);
    }

    public static function getOfertasMes()
    {
        $data[] = "CASE";
        foreach (Util::getMes() AS $k => $label):
            $data[] = " WHEN DATE_FORMAT(ofertas.data, '%m') = {$k} THEN '{$label}'";
        endforeach;
        $data[] = "ELSE DATE_FORMAT(ofertas.data, '%m')";
        $data[] = "END as mes";

        return implode("\n", $data);
    }

    public static function getOfertasNatureza()
    {
        $data[] = "CASE";
        foreach (DataHelpers::getNatureza() AS $k => $label):
            $data[] = " WHEN ofertas.natureza = {$k} THEN '{$label}'";
        endforeach;
        $data[] = "ELSE ofertas.natureza";
        $data[] = "END as natureza";

        return implode("\n", $data);
    }


}