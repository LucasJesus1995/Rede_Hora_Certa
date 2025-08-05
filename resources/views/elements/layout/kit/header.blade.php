<?php
$kit_white =  !empty($kit_white) ?  $kit_white : false;

$data = \App\Http\Helpers\Anamnese::DataHeader($agenda);

$nascimento = !empty($data['nascimento']) ? explode("-", $data['nascimento']) : null;

$idade = is_array($nascimento) ? \Carbon\Carbon::createFromDate($nascimento[0], $nascimento[1], $nascimento[2])->age : null;

if ($idade != null && $idade >= 65) {
    $data['preferencial'] = true;
}

$exibe_linha = !isset($exibe_linha) ? true : $exibe_linha;

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

$hidden_logo_header = !isset($hidden_logo_header) ? false : $hidden_logo_header;
?>
<table width="100%" cellpadding="0" cellspacing="0">

    <tr>
        <td width="*">
            <table width="100%" cellpadding="5">
                <tr>
                    @if(isset($data['preferencial']) && $data['preferencial'])
                        <td>
                            <div style="padding: 0px 15px;  margin: 0 0 0 -7px; font-size: 60px; border: 1px solid #000">P</div>
                        </td>
                    @endif
                    <td width="70%">
                        @if($exibe_linha)
                            <h1 style="margin-top: 0px; text-align: left">
                                {{$data['linha_cuidado']}} <br/><span>{!! $_arena['nome'] !!}</span>
                                <p style="margin-top: 10px; font-weight: normal">{{$tempo_atendimento}}</p>
                            </h1>
                        @endif
                        @if(!$hidden_logo_header)
                            @if(!empty($logo_drsaude) && $logo_drsaude)
                                <img class='img-responsive' style='height:50px; margin-bottom: 10px' src='/src/image/logo/dr_saude.png'>
                            @endif
                        @endif
                    </td>
                    <td class="logo" style="text-align: right">

                        @if(empty($logo_drsaude) || !$logo_drsaude)
                            {!! \App\Http\Helpers\Util::getUriImageProjetoByAgenda($agenda) !!}
                        @else
                            <img class='img-responsive' style='height: 60px;  margin-bottom: 10px' src='/src/image/logo/cies.png'>
                        @endif

                    </td>
                </tr>
            </table>
            <hr style="margin: 0; margin-top: -10px"/>
            <table width="100%" class="formulario" cellspacing="3">
                <tr>
                    <th>
                        <label>Nome: </label>
                    </th>
                    <td colspan="3">
                        <span class="line">
                            {!! \App\Pacientes::nomeSocialLayout($data['nome'], $data['nome_social']) !!}
                        </span>
                    </td>
                    <th>
                        <label>Código: </label>
                    </th>
                    <td>
                        <span class="line"> {{$data['paciente']}}</span>
                    </td>
                </tr>
                <tr>
                    <th>Mãe:</th>
                    <td>{{$data['mae']}}</td>
                    <th>RG:</th>
                    <td>{{$data['rg']}}</td>
                    <th>D. Nasc.:</th>
                    <td>{{ !empty($data['nascimento']) ? \App\Http\Helpers\Util::DB2User($data['nascimento']) : null }}
                        <small style="font-size: 9px;">({{$idade}} anos)</small>
                    </td>
                </tr>
                <tr>
                    <th>Sexo:</th>
                    <td>{{!empty($data['sexo']) ?  \App\Http\Helpers\Util::Sexo($data['sexo']) : null }}</td>
                    <th>CNS:</th>
                    <td>{{$data['cns']}}</td>
                    <th>Data:</th>
                    <td>{{ \App\Http\Helpers\Util::DBTimestamp2UserDate($data['data'])}} </td>
                </tr>

                <tr>
                    <td width="70px" style="border: none"></td>
                    <td width="230px" style="border: none"></td>
                    <td width="70px" style="border: none"></td>
                    <td width="200px" style="border: none"></td>
                    <td width="100px" style="border: none"></td>
                    <td width="130px" style="border: none"></td>
                </tr>
            </table>
        </td>
        @if($box)
            <td width="300px" style="padding-left: 20px">
                <table width="100%" class="table-border" cellpadding="3" cellspacing="0">
                    <tr>
                        <th width="45%">Agendamento:</th>
                        <td width="*">{{ \App\Http\Helpers\Util::DBTimestamp2UserTime2($data['data'])}}</td>
                    </tr>
                    <tr>
                        <th>Preferencial:</th>
                        <td>{{ (isset($data['preferencial']) && $data['preferencial']) ? "Sim"  : "Não"  }}</td>
                    </tr>
                    <tr>
                        <th> Atendimento:</th>
                        <td>{{$hora_atendimento}}</td>
                    </tr>
                    <tr>
                        <th>Recepcionista:</th>
                        <td>{{Str_limit(Auth::user()->name,28)}}</td>
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