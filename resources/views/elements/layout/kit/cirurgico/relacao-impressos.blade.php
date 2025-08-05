<?php

if(!isset($kit_white) || !$kit_white){
    $paciente = (Object)\App\Pacientes::get($agenda->paciente);
}else{

}
?>
@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

<div class="bloco " style="margin: 0; margin-top: 10px;">

    <table width="100%" class="border" style="margin: 5px">
        <tr>
            <th width="60%" class="title" style="text-align: left">
                PROCEDIMENTO REALIZADO COM SUCESSO?
            </th>
            <th width="10%" class="title">
                (&nbsp;&nbsp;&nbsp;&nbsp;) SIM
            </th>
            <th width="10%" class="title">
                (&nbsp;&nbsp;&nbsp;&nbsp;) NÃO
            </th>
            <th width="10%" class="title" colspan="2"></th>
        </tr>
        <tr>
            <th width="60%" rowspan="2" class="title">
                RELAÇÃO DE IMPRESSOS
            </th>
            <th width="20%" colspan="4" class="title">
                CHECAGEM
            </th>
        </tr>
        <tr>
            <th width="20%" colspan="2" class="title">
                ENFERMAGEM
            </th>
            <th width="20%" colspan="2" class="title">
                ADMINISTRATIVO
            </th>
        </tr>

        @foreach(\App\Http\Helpers\Anamnese::getRelacaoImpressos($agenda->linha_cuidado) AS $row)
            <tr class=" {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                <td>{!! $row !!}</td>
                <td style="text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;) SIM</td>
                <td style="text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;) NÃO</td>
                <td style="text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;) SIM</td>
                <td style="text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;) NÃO</td>
            </tr>
        @endforeach
    </table>


    <div style="text-align: right; text-transform: uppercase; margin-top: 100px">CARIMBO E VISTO DO RESPONSÁVEL ASSISTENCIAL</div>
</div>


<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>