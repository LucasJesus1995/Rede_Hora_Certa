<?php

if(!isset($kit_white) || !$kit_white){
    $paciente = (Object)\App\Pacientes::get($agenda->paciente);
}else{
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

<h1 class="title">DECLARAÇÃO DE RECEBIMENTO DE INFORMAÇÕES</h1>

<div class="font-contrato-size">

    Por determinação explícita de minha vontade, eu <strong>@if(!empty($paciente->nome_social)) {!! $paciente->nome_social !!} @else {!!  $paciente->nome !!} @endif</strong> portador(a) da identidade n&ordm; @if(!empty($paciente->rg)) <strong>{!! $paciente->rg !!}</strong>  @else
        _______________________  @endif por este termo, voluntariamente autorizo:
    <p>@include('elements.layout.kit.cirurgico.assinatura-medico')</p>
    Todas as informações necessárias para o bom entendimento dos procedimentos aos quais irei me submeter
    <br/><br/>Declaro estar ciente de tudo, inclusive dos riscos oferecidos e do resultado que pode ser apenas relativo, já que determinado pela individualidade de cada ser e na dependência da resposta do meu organismo.
    <br/><br/>Declaro que me foi oferecido a oportunidade de esgotar quaisquer dúvidas sobre procedimento(s) qual(is) irei me submeter, bem como dos riscos inerentes aos mesmos.
    <br/><br/>Declaro, pois, que <strong>recebi</strong>, <strong>li e entendi</strong> os seguintes documentos informativos:

    <div class="line-height-22">
        (&nbsp;x&nbsp;) CONSENTIMENTO INFORMADO
        <br />(&nbsp;&nbsp;&nbsp;&nbsp;) OUTROS. Especificar: .................................................................................................................................................
    </div>

    <br/>

    <div style="margin-top: 10px">
        @include('elements.layout.kit.aux.lei-lgpd')
    </div>
    <div style="margin-top: 150px; text-align: right">

        {!! \App\Http\Helpers\Util::dateExtensoCidade($agenda->data, "São Paulo"); !!}
    </div>

    <div>
        <table width="100%" border="1" >
            <tr>
                <td width="26%">
                    <div style="margin-top: 50px; border-top: 1px solid #000; width: 220px; text-align: center">
                        @if(!empty($paciente->nome_social)) {!! $paciente->nome_social !!} @else {!!  $paciente->nome !!} @endif
                    </div>
                </td>
                <td width="15%" style="text-align: right">
                    <div style="margin-top: 50px; border-top: 1px solid #000; width: 220px; text-align: center">
                        ACOMPANHANTE
                    </div>
                </td>
                <td width="15%" nowrap>
                    <div style="margin-top: 35px; text-align: left">
                    RG: __________________
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>
<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>