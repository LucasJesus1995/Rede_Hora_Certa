<?php

namespace App\Http\Helpers\Exportacao;


use App\AtendimentoProcedimentos;
use App\Cid;
use App\Cidades;
use App\Http\Helpers\Util;
use App\Lotes;
use App\Profissionais;

abstract class ExportacaoProducao
{

    protected function getContrato($lote)
    {
        $data = Lotes:: select(array('lotes.nome', 'lotes.codigo'))
            ->where('id', $lote)
            ->get()
            ->toArray();

        return !empty($data[0]) ? $data[0] : null;
    }

    protected function getLote($lote)
    {
        return Lotes::find($lote);
    }

    protected function getEnderecoTipoDescricao($endereco_tipo)
    {
        $endereco_tipo = Util::EnderecoTipo(Util::StrPadLeft($endereco_tipo, 3));

        $tipo = is_array($endereco_tipo) ? "Rua" : $endereco_tipo;
        $descricao = explode(" - ", $tipo);

        return Util::String2DB(!empty($descricao[1]) ? $descricao[1] : $descricao[0]);
    }

    protected function getIbge($cidade = 5271)
    {
        $ibge = 3550308;
        $cidade = Cidades::get($cidade);
        if (!empty($cidade->ibge)) {
            $ibge = $cidade->ibge;
        }

        return $ibge;
    }

    protected function getMedico($medico)
    {
        $medico = Profissionais::getMedicoByID($medico);

        return !empty($medico->nome) ? $medico->nome : null;
    }

    protected function getMedicoCNS($medico)
    {
        $medico = Profissionais::getMedicoByID($medico);

        return !empty($medico->cns) ? $medico->cns : null;
    }

    protected function getProcedimentoPrincipal($atendimento)
    {

        $atendimento_procedimentos = AtendimentoProcedimentos::getProcedimentoPrincipalByAtendimento($atendimento);

        return !is_null($atendimento_procedimentos) ? $atendimento_procedimentos->sus : null;
    }

    protected function getCodigoCID($cid = null)
    {
        $data = null;

        if (!empty($cid)) {
            $cid = Cid::get($cid);
            $data = !empty($cid->codigo) ? $cid->codigo : null;
        }

        return $data;
    }

}