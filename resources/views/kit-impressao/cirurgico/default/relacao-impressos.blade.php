<?php

if (!isset($kit_white) || !$kit_white) {
    $paciente = (Object)\App\Pacientes::get($agenda->paciente);
} else {

}
?>
<div style='page-break-before:always;' class="kit-impressao">
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

    <div class="bloco " style="margin: 0; padding: 0; border: none !important;">

        <table width="100%" class="" style="border: 1px solid #000">
            <tr>
                <th width="60%" class="title border" style="text-align: left">
                    PROCEDIMENTO REALIZADO COM SUCESSO?
                </th>
                <th width="10%" class="title border">
                    (&nbsp;&nbsp;&nbsp;&nbsp;) SIM
                </th>
                <th width="10%" class="title border">
                    (&nbsp;&nbsp;&nbsp;&nbsp;) NÃO
                </th>
                <th width="10%" class="title border" colspan="2"></th>
            </tr>
            <tr>
                <th width="60%" rowspan="2" class="title border">
                    RELAÇÃO DE IMPRESSOS
                </th>
                <th width="20%" colspan="4" class="title border">
                    CHECAGEM
                </th>
            </tr>
            <tr>
                <th width="20%" colspan="2" class="title border">
                    ENFERMAGEM
                </th>
                <th width="20%" colspan="2" class="title border">
                    ADMINISTRATIVO
                </th>
            </tr>

            @foreach(\App\Http\Helpers\Anamnese::getRelacaoImpressos($agenda->linha_cuidado) AS $row)
                <tr class=" {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!} border">
                    <td>{!! $row !!}</td>
                    <td style="text-align: center; border-left: 1px solid #000;">(&nbsp;&nbsp;&nbsp;&nbsp;) SIM</td>
                    <td style="text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;) NÃO</td>
                    <td style="text-align: center; border-left: 1px solid #000;">(&nbsp;&nbsp;&nbsp;&nbsp;) SIM</td>
                    <td style="text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;) NÃO</td>
                </tr>
            @endforeach
        </table>


        <div style="text-align: right; text-transform: uppercase; margin-top: 120px">CARIMBO E VISTO DO RESPONSÁVEL ASSISTENCIAL</div>
    </div>


    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>