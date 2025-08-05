<?php
$arena = (Object)\App\Arenas::get($agenda->arena);
$paciente = \App\Pacientes::find($agenda->paciente);
$atendimento = \App\Atendimentos::getByAgenda($agenda->id);

$medico = null;
if ($agenda->medico) {
    $_medico = \App\Profissionais::getMedicoByID($agenda->medico);

    if (!empty($_medico)) {
        $medico = $_medico;
    }
}
?>
<style type="text/css">
    html {
        margin-bottom: 25px !important;
    }
</style>
<div class="ficha-autorizacao box border" style="padding: 5px;  margin: 0">

    <table width="100%" style="margin: 0 1px; margin-left: 0.5px !important; margin-bottom: 5px">
        <tr>
            <td style="width: 200px;" class="border">
                <table style="width: 100%;" border="1" style="border-spacing: 5px !important; margin-left: -1px">
                    <tr>
                        <td style="width: 70px">
                            <img src="src/image/logo/sus.png" height="35px" style="margin-top: 3px"/>
                        </td>
                        <td style="width: 65px">
                            <div style="text-align: center; font-weight: bold;  font-size: 10px !important; margin-top: 3px ">
                                Sistema Único de Saúde
                            </div>
                        </td>
                        <td style="width: 60px">
                            <div style="text-align: center; font-weight: bold; font-size: 10px !important; margin-top: 9px ">
                                Ministério da Saúde
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="border">
                <div style="font-weight: bold; text-align: center; font-size: 14px !important; padding: 5px 30px; margin: 0">
                    LAUDO PARA SOLICITAÇÃO/AUTORIZAÇÃO DE PROCEDIMENTO AMBULATORIAL
                </div>
            </td>
        </tr>
    </table>

    <div class="border">
        <table style="width: 100%;">
            <tr>
                <th colspan="2" style="">IDENTIFICAÇÃO DO ESTABELECIMENTO DE SAÚDE (SOLICITANTE)</th>
            </tr>
            <tr>
                <td>
                    <div class="label-descricao">
                        <label>1 - Nome do estabelecimento de saúde solicitante</label>
                        &nbsp;
                    </div>
                </td>
                <td style="width: 120px !important;">
                    <div class="label-descricao line">
                        <label>2 - CNES</label>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento(null, 7) !!}
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="2" style="">IDENTIFICAÇÃO DO PACIENTE</th>
            </tr>
            <tr>
                <td>
                    <div class="label-descricao">
                        <label>3 - Nome do paciente</label>
                        {!! $paciente->getNomeSocialLayout() !!}
                    </div>
                </td>
                <td style="width: 120px !important;">
                    <div class="label-descricao line align-center">
                        <label>4 - N&ordm; DO PRONTUÁRIO</label>&nbsp;

                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td width="48.5%">
                    <div class="label-descricao">
                        <label>5 - CARTÃO NACIONAL DE SAÚDE (CNS)</label>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento($paciente->cns, 15) !!}
                    </div>
                </td>
                <td width="15.5%">
                    <div class="label-descricao">
                        <label>6 - DATA DE NASCIMENTO</label>
                        <?php
                        $nascimento = explode("-", $paciente->nascimento);
                        ?>
                        @if(!empty($nascimento) && count($nascimento) == 3)
                            <span style="margin-left: 10%;"> {!! $nascimento[2] !!} / {!! $nascimento[1] !!} / {!! $nascimento[0] !!}</span>
                        @endif
                    </div>
                </td>
                <td width="20.5%">
                    <div class="label-descricao">
                        <label>7 - SEXO</label>

                        <table width="100%" cellpadding="" style="margin: 3px 0">
                            <tr>
                                <td width="50%" style="position: relative;" nowrap>
                                    <div style="position: absolute; top: -3px"><span class="quadrado">@if($paciente->sexo == 1)&times;@else&nbsp; @endif</span> Masc.</div>
                                </td>
                                <td width="50%" style="position: relative" nowrap>
                                    <div style="position: absolute; top: -3px"><span class="quadrado">@if($paciente->sexo == 2)&times;@else&nbsp; @endif</span> Fem.</div>
                                </td>
                            </tr>
                        </table>
                        &nbsp;
                    </div>
                </td>
                <td width="17.5%">
                    <div class="label-descricao">
                        <label>8 - RAÇA/COR</label>
                        <?php
                        $raca_cor = App\Http\Helpers\Util::RacaCor($paciente->raca_cor);
                        ?>
                        {!! !is_array($raca_cor) ? $raca_cor : null  !!}&nbsp;
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="label-descricao">
                        <label>9 - Nome da mãe</label>
                        {!! $paciente->mae !!}&nbsp;
                    </div>
                </td>
                <td style="width: 120px !important;">
                    <div class="label-descricao line">
                        <label>10 - Telefone de contato</label>
                        @if(!empty($paciente->celular))
                            &nbsp;{!! \App\Http\Helpers\Mask::telefone($paciente->celular) !!}
                        @else
                            &nbsp;{!! \App\Http\Helpers\Mask::telefone($paciente->telefone_residencial) !!}
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label-descricao">
                        <label>11 - Nome do responsável</label>
                        @if(!empty($paciente->contato))
                            {!! $paciente->contato !!}
                        @else
                            {!! $paciente->mae !!}
                        @endif
                        &nbsp;
                    </div>
                </td>
                <td style="width: 120px !important;">
                    <div class="label-descricao line">
                        <label>12 - Telefone de contato</label>
                        &nbsp;{!! \App\Http\Helpers\Mask::telefone($paciente->telefone_contato) !!}&nbsp;
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="label-descricao">
                        <label>13 - Endereço (Rua, N&ordm;, Bairro)</label>
                        {!! \App\Http\Helpers\StringHelpers::getEnderecoPaciente($paciente) !!}&nbsp;
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td width="56%">
                    <div class="label-descricao">
                        <label>14 - Município de Residência</label>
                        {!! \App\Http\Helpers\DataHelpers::getCidade($paciente->cidade) !!}
                    </div>
                </td>
                <td width="17.5%">
                    <div class="label-descricao align-center">
                        <label>15 - Cód. IBGE Município</label>
                        &nbsp;{!! \App\Http\Helpers\DataHelpers::getCidadeIBGE($paciente->cidade) !!}
                    </div>
                </td>
                <td width="8%">
                    <div class="label-descricao">
                        <label>16 - UF</label>
                        <?php
                        $estado_sigla = \App\Http\Helpers\DataHelpers::getCidadeEstadoSigla($paciente->cidade);
                        ?>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento($estado_sigla, 2) !!}
                    </div>
                </td>
                <td width="18.5%">
                    <div class="label-descricao">
                        <label>17 - CEP</label>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento($paciente->cep, 8) !!}
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <th colspan="3" style="">Procedimento Solicitado</th>
            </tr>
            <tr>
                <td width="35%">
                    <div class="label-descricao">
                        <label>18 - Código do procedimento principal</label>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento(null, 10) !!}
                    </div>
                </td>
                <td width="50%">
                    <div class="label-descricao">
                        <label>19 - Nome do procedimento principal</label>
                        <br>
                    </div>
                </td>
                <td width="15%">
                    <div class="label-descricao">
                        <label>20 - Qtde</label>
                        <br>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="3" style="">Procedimento(s) Secundário(s)</th>
            </tr>
            <tr>
                <td width="35%">
                    <div class="label-descricao">
                        <label>21 - Código do procedimento secundário</label>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento(null, 10) !!}
                    </div>
                </td>
                <td width="50%">
                    <div class="label-descricao">
                        <label>22 - Nome do procedimento secundário</label>
                        <br>
                    </div>
                </td>
                <td width="15%">
                    <div class="label-descricao">
                        <label>23 - Qtde</label>
                        <br>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="35%">
                    <div class="label-descricao">
                        <label>24 - Código do procedimento secundário</label>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento(null, 10) !!}
                    </div>
                </td>
                <td width="50%">
                    <div class="label-descricao">
                        <label>25 - Nome do procedimento secundário</label>
                        <br>
                    </div>
                </td>
                <td width="15%">
                    <div class="label-descricao">
                        <label>26 - Qtde</label>
                        <br>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="35%">
                    <div class="label-descricao">
                        <label>27 - Código do procedimento secundário</label>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento(null, 10) !!}
                    </div>
                </td>
                <td width="50%">
                    <div class="label-descricao">
                        <label>28 - Nome do procedimento secundário</label>
                        <br>
                    </div>
                </td>
                <td width="15%">
                    <div class="label-descricao">
                        <label>29 - Qtde</label>
                        <br>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="35%">
                    <div class="label-descricao">
                        <label>30 - Código do procedimento secundário</label>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento(null, 10) !!}
                    </div>
                </td>
                <td width="50%">
                    <div class="label-descricao">
                        <label>31 - Nome do procedimento secundário</label>
                        <br>
                    </div>
                </td>
                <td width="15%">
                    <div class="label-descricao">
                        <label>32 - Qtde</label>
                        <br>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="35%">
                    <div class="label-descricao">
                        <label>33 - Código do procedimento secundário</label>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento(null, 10) !!}
                    </div>
                </td>
                <td width="50%">
                    <div class="label-descricao">
                        <label>34 - Nome do procedimento secundário</label>
                        <br>
                    </div>
                </td>
                <td width="15%">
                    <div class="label-descricao">
                        <label>35 - Qtde</label>
                        <br>
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <th colspan="4" style="">Justificativa do(s) procedimento(s) solicitado(s)</th>
            </tr>
            <tr>
                <td width="49%">
                    <div class="label-descricao">
                        <label>36 - Descrição do diagnóstico</label>&nbsp;
                        @if($agenda->linha_cuidado == 19)
                            <b style="float:right">OLHO_______</b>
                        @endif
                    </div>
                </td>
                <td width="19%">
                    <div class="label-descricao">
                        <label>37 - CID 10 Principal</label>&nbsp;
                        @if($agenda->linha_cuidado == 19)
                            <b style="float:left">H25.9</b>
                        @else
                            <br>
                        @endif
                    </div>
                </td>
                <td width="19.5%">
                    <div class="label-descricao">
                        <label>38 - CID 10 Secundário</label>
                        <br>
                    </div>
                </td>
                <td width="23%">
                    <div class="label-descricao">
                        <label>39 - CID 10 Causas Associadas</label>
                        <br>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="label-descricao" style="height: 110px">
                        <label>40 - Observações</label>
                        @if($agenda->linha_cuidado == 19)
                            <p style="font-weight: bold; line-height: 22px; padding: 5px">
                                Biometria:<br/>
                                Acuidade:<br/>
                                Ceratometria:<br/>
                                Biomicroscopia:<br/>
                            </p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <th colspan="4" style="">Solicitação</th>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="label-descricao">
                        <label>41 - Nome do profissional solicitante</label>&nbsp;
                        @if(isset($medico)) {!! $medico->nome !!} @endif
                    </div>
                </td>
                <td>
                    <div class="label-descricao">
                        <label>42 - Data da solicitação</label>
                        <span style="margin: 15%;">{!! str_replace("/"," / ", \App\Http\Helpers\Util::DBTimestamp2UserDate($agenda->data)) !!}</span><br>
                    </div>
                </td>
                <td rowspan="2">
                    <div class="label-descricao">
                        <label>45 - Assinatura e carimbo (N&ordm; Registro do conselho)</label>
                        <br><br><br><br>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label-descricao">&nbsp;
                        <label>43 - Documento</label>
                        <span class="quadrado">@if(isset($medico->cns) && !empty($medico->cns)) &times; @else &nbsp; @endif</span> CNS
                        <span style="float: right; padding-right: 10px"><span class="quadrado">@if(isset($medico->cns) && empty($medico->cns) && !empty($medico->cpf)) &times; @else &nbsp; @endif</span>CPF</span>
                    </div>
                </td>
                <td colspan="2">
                    <div class="label-descricao">
                        <label>44 - N&ordm; Documento (CNS/CPF) do profissional solicitante</label>
                        &nbsp;
                        @if(isset($medico->cns) && !empty($medico->cns))
                            {!! \App\Http\Helpers\Mask::SUS($medico->cns) !!}
                        @else
                            @if(isset($medico->cpf) && !empty($medico->cpf))
                                {!! \App\Http\Helpers\Mask::Cpf($medico->cpf) !!}
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="4" style="">Autorização</th>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="label-descricao">
                        <label>46 - Nome do profissional autorizador</label>
                        <br>
                    </div>
                </td>
                <td width="18.5%">
                    <div class="label-descricao">
                        <label>47 - Cód. Órgão Emissor</label>
                        <br>
                    </div>
                </td>
                <td rowspan="2">
                    <div class="label-descricao">
                        <label>52 - N&ordm; da Autorização (APAC)</label>
                        <br><br><br><br>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label-descricao">
                        <label>48 - Documento</label>&nbsp;
                        <span class="quadrado">&nbsp; </span> CNS
                        <span style="float: right; padding-right: 10px"><span class="quadrado">&nbsp; </span>CPF</span>
                    </div>
                </td>
                <td colspan="2">
                    <div class="label-descricao">
                        <label>49 - N&ordm; Documento (CNS/CPF) do profissional autorizador</label>
                        <br>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label-descricao">
                        <label>50 - Data da autorização</label>
                        <br>
                    </div>
                </td>
                <td colspan="2">
                    <div class="label-descricao">
                        <label>51 - Assinatura e carimbo (N&ordm; do registro do conselho)</label>
                        <span style="float: right;">____/____/____</span><br>
                    </div>
                </td>
                <td width="32%">
                    <div class="label-descricao">
                        <label>53 - Período de validade da APAC</label>
                        <span>____/____/____</span><span style="padding-left:17px">a</span><span style="padding-left:17px">____/____/____</span>
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <th colspan="2" style="">Identificação do Estabelecimento de Saúde (Executante)</th>
            </tr>
            <tr>
                <td width="80%">
                    <div class="label-descricao">
                        <label>54 - Nome Fantasia do Estabelecimento de Saúde Executante</label>
                        CIES
                    </div>
                </td>
                <td width="20%">
                    <div class="label-descricao">
                        <label>55 - CNES</label>
                        {!! \App\Http\Helpers\StringHelpers::pipeTDFichaAtendimento($arena->cnes, 7) !!}
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>