<?php
$kit_white = !empty($kit_white) ? $kit_white : false;
$linha_cuidado = !empty($linha_cuidado) ? $linha_cuidado : null;
$agenda = !empty($agenda) ? $agenda : null;

$exibe_linha = !isset($exibe_linha) ? true : $exibe_linha;

$hidden_logo_header = !isset($hidden_logo_header) ? false : $hidden_logo_header;

if (!$kit_white) {
    $data = \App\Http\Helpers\Anamnese::DataHeader($agenda);

    $nascimento = !empty($data['nascimento']) ? explode("-", $data['nascimento']) : null;

    $idade = is_array($nascimento) ? \Carbon\Carbon::createFromDate($nascimento[0], $nascimento[1], $nascimento[2])->age : null;

    if ($idade != null && $idade >= 65 && !isset($hidden_preferencial)) {
        $data['preferencial'] = true;
    }

    $atendimento = \App\Atendimentos::ByAgenda($agenda);
    $atendimento_tempo = \App\AtendimentoTempo::getByAtendimento($atendimento->id);

    $hora_atendimento = null;
    $tempo_atendimento = null;
    if (!empty($atendimento_tempo->id)) {
        if (!empty($atendimento_tempo->recepcao_in)) {
            $hora_atendimento = \App\Http\Helpers\Util::DBTimestamp2User($atendimento_tempo->recepcao_in);
        }

        if (!empty($atendimento_tempo->recepcao_in) && !empty($atendimento_tempo->recepcao_out)) {
            $tempo_atendimento = \App\Http\Helpers\Util::TempoAtendimentoDate($atendimento_tempo->recepcao_in, $atendimento_tempo->recepcao_out);
        }
    }

    $_agenda = \App\Agendas::get($agenda);
    $_arena = \App\Arenas::get($_agenda['arena']);


//Remove a logo do DrSaude das arenas abaixo.
    if (in_array($_arena['id'], [36])) {
        $hidden_logo_header = true;
        $exibe_linha = true;
    }

    $linha_cuidado_sub_especialidade = \App\Http\Helpers\Cirurgico\KitImpressaoHelpers::getSubEspecialidades($_agenda['linha_cuidado']);

    $desc_sub_especialidade = is_array($linha_cuidado_sub_especialidade) && array_key_exists($sub_especialidade, $linha_cuidado_sub_especialidade) ? $linha_cuidado_sub_especialidade[$sub_especialidade] : null;
} else {
    $data['preferencial'] = null;
    $data['nome'] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.";
    $data['nome_social'] = null;
    $data['mae'] = null;
    $data['paciente'] = null;
    $data['data'] = null;
    $data['sexo'] = null;
    $data['nascimento'] = null;
    $data['rg'] = null;
    $data['cns'] = null;
    $_arena['nome'] = null;
    $data['linha_cuidado'] = !empty($linha_cuidado->nome) ? $linha_cuidado->nome : null;
    $idade = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $_agenda['data'] = null;
    $desc_sub_especialidade = null;
    $hora_atendimento = null;

    $desc_sub_especialidade = null;
    if (!empty($linha_cuidado)) {
        $linha_cuidado_sub_especialidade = \App\Http\Helpers\Cirurgico\KitImpressaoHelpers::getSubEspecialidades($linha_cuidado->id);
        $desc_sub_especialidade = is_array($linha_cuidado_sub_especialidade) && array_key_exists($sub_especialidade, $linha_cuidado_sub_especialidade) ? $linha_cuidado_sub_especialidade[$sub_especialidade] : null;
    }
}
?>
<div class="header">
    <table width="600px" class="no-border">
        <tr>
            <td width="480px">
                <table width="100%" cellpadding="5" border="1">
                    <tr>
                        <td width="*">
                            <div>
                                @if(isset($data['preferencial']) && $data['preferencial'])
                                    <div style="float: left; padding: 5px; margin-right: 4px; margin-left: -3px; background: #CCC; font-weight: bold; font-size: 45px !important; color: #FFF; width: 50px; text-align: center;">
                                        P
                                    </div>
                                @endif

                                @if($exibe_linha)
                                    <div style="float: left;">
                                        <h1 style="margin-top: 0px; text-align: left">
                                            {{$data['linha_cuidado']}} @if(!is_null($desc_sub_especialidade)) - {!! \App\Http\Helpers\Util::String2DB($desc_sub_especialidade) !!}  @endif <br/>
                                            <span>{!! $_arena['nome'] !!}</span>
                                        </h1>
                                    </div>
                                @endif
                                @if(!$hidden_logo_header)
                                    @if(!empty($logo_drsaude) && $logo_drsaude)
                                        {{--<img class='img-responsive' style='height:60px; margin-top: 0' src='src/image/logo/dr_saude.png'>--}}
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td class="logo" valign="center" nowrap style="text-align: right" width="40px">
                            @if(empty($logo_drsaude) || !$logo_drsaude)
                                {!! \App\Http\Helpers\Util::getUriImageProjetoByAgenda($agenda) !!}
                            @else
                                <img style='height: 60px; margin-bottom: 10px' src='src/image/logo/cies.png'>
                            @endif
                        </td>
                    </tr>
                </table>

                <table width="100%" class="line-td-bottom" style="margin-top: 0px">
                    <tr>
                        <th class="right">Nome:</th>
                        <td colspan="3">{!! \App\Pacientes::nomeSocialLayout($data['nome'], $data['nome_social']) !!}</td>
                        <th nowrap class="right">Cód. CIES:</th>
                        <td>{{$data['paciente']}}</td>
                    </tr>
                    <tr>
                        <th class="right">Sexo:</th>
                        <td>{{!empty($data['sexo']) ?  \App\Http\Helpers\Util::Sexo($data['sexo']) : null }}</td>
                        <th class="right">RG:</th>
                        <td>{{$data['rg']}}</td>
                        <th class="right">  Nasc.:</th>
                        <td>{{ !empty($data['nascimento']) ? \App\Http\Helpers\Util::DB2User($data['nascimento']) : null }}
                            <small style="font-size: 9px;">({{$idade}})</small>
                        </td>
                    </tr>
                    <tr>
                        <th class="right" nowrap>Nome Mãe:</th>
                        <td>{{ \App\Http\Helpers\Util::NomeSobrenome($data['mae']) }}</td>
                        <th class="right">CNS:</th>
                        <td>{{$data['cns']}}</td>
                        <th class="right">Data:</th>
                        <td>{{ \App\Http\Helpers\Util::DBTimestamp2User2($_agenda['data'])}} </td>
                    </tr>

                    <tr>
                        <td width="9%" style="border: none !important;"></td>
                        <td width="33%" style="border: none !important;"></td>
                        <td width="7%" style="border: none !important;"></td>
                        <td width="20%" style="border: none !important;"></td>
                        <td width="10%" style="border: none !important;"></td>
                        <td width="22%" style="border: none !important;"></td>
                    </tr>
                </table>
            </td>
            @if($box)
                <td width="120px">
                    <table width="*" class="table-border" cellpadding="1" cellspacing="0">
                        <tr>
                            <th width="40px">Agendamento:</th>
                            <td width="80px">
                                {{ \App\Http\Helpers\Util::DBTimestamp2UserTime2($data['data'])}}
                                @if($kit_white)
                                    &nbsp;
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Preferencial:</th>
                            <td>@if(!$kit_white) {{ (isset($data['preferencial']) && $data['preferencial']) ? "Sim"  : "Não"  }} @endif</td>
                        </tr>
                        <tr>
                            <th>Atendimento:</th>
                            <td>{{$hora_atendimento}}</td>
                        </tr>
                        <tr>
                            <th>Recepcionista:</th>
                            <td>@if (!$kit_white)  {{Str_limit(Auth::user()->name,28)}} @endif</td>
                        </tr>
                        <tr>
                            <th>Enfermeiro (a):</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Médico (a):</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Digitador (a):</th>
                            <td></td>
                        </tr>
                    </table>
                </td>
            @endif
        </tr>
    </table>
</div>
<hr style="margin-bottom: 5px;" />