<?php
if (!isset($kit_white) || !$kit_white) {
    $paciente = (Object)\App\Pacientes::get($agenda->paciente);
} else {
    $agenda = new stdClass();
    $paciente = new stdClass();
    $paciente->nome_social = null;
    $paciente->nome = "PACIENTE";

    $agenda->id = null;
    $agenda->data = date('Y-m-d');
    $agenda->linha_cuidado = $linha_cuidado->id;
}
?>
@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

<h1 class="title">TERMO DE CONSENTIMENTO INFORMADO</h1>

<div class="font-contrato-size" style="">

    Por determinação explícita de minha vontade, eu <strong>@if(!empty($paciente->nome_social)) {!! $paciente->nome_social !!} @else {!!  $paciente->nome !!} @endif</strong> portador(a) da identidade
    n&ordm; @if(!empty($paciente->rg)) <strong>{!! $paciente->rg !!}</strong>  @else
        _______________________  @endif por este termo, voluntariamente autorizo:
    <p>@include('elements.layout.kit.cirurgico.assinatura-medico')</p>
    a realizar o(s) seguinte(s) procedimento(s) cirúrgico(s) em minha pessoa (ou na pessoa de meu dependente menor): ____________________________________________________ ,
    bem como os cuidados e tratamentos médicos necessários dele decorrentes.

    <br/>
    <br/>
    <strong>O(s) procedimento(s) acima autorizado(s) me foi explicado claramente, por isso entendo que:</strong>

    @foreach(\App\Http\Helpers\Anamnese::termoConsentimentoInformado($agenda->linha_cuidado, $sub_especialidade) AS $k => $row)
        <div style="margin-left: 5px; text-align: justify"><strong>{!! $k !!}.</strong> &nbsp; {!! $row !!}</div>
    @endforeach

    <br/>

    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>

    <div style='page-break-before:always;'>
        <br/><br/>

        @if ($agenda->linha_cuidado == 45 and $sub_especialidade == 1)
            <p><strong>4. Portanto, de posse dessas informações:</strong></p>
            @foreach(\App\Http\Helpers\Anamnese::termoConsentimentoInformadoReconhecimento($agenda->linha_cuidado, $sub_especialidade) AS $row)
                <div style="margin-left: 20px">&bull; {!! $row !!}</div>
            @endforeach
        @else
            <p><strong>n. Portanto, de posse dessas informações</strong></p>
            @foreach(\App\Http\Helpers\Anamnese::termoConsentimentoInformadoReconhecimento($agenda->linha_cuidado, $sub_especialidade) AS $row)
                <div style="margin-top: 5px; margin-left: 20px">&bull; {!! $row !!}</div>
            @endforeach
        @endif

        <div style="margin-top: 10px;">
            @include('elements.layout.kit.aux.lei-lgpd')
        </div>

        <div style="margin-top: 20px; text-align: right">
            {!! \App\Http\Helpers\Util::dateExtensoCidade($agenda->data, "São Paulo"); !!}
        </div>

        <div>
            <table width="100%">
                <tr>
                    <td width="30%" colspan="2">
                        <div style="margin-top: 45px; border-top: 1px solid #000; width: 250px; text-align: center">
                            @if(!empty($paciente->nome_social)) {!! $paciente->nome_social !!} @else {!!  $paciente->nome !!} @endif
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%" class="">
                <tr>
                    <td width="200px">&nbsp;</td>
                    <td width="200px" style="text-align: right">
                        <div style="margin-top:45px; border-top: 1px solid #000;  text-align: center">
                            ACOMPANHANTE
                        </div>
                    </td>
                    <td width="100px">
                        <div style="margin-top: 37px; text-align: right">
                            RG: _______________________
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        <div style="margin-top: 50px; border-top: 1px solid #000; text-align: center">
                            NOME LEGÍVEL DO ACOMPANHANTE
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>