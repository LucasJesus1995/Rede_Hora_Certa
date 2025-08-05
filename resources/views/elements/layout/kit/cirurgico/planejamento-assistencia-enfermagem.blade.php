<?php
if(!isset($kit_white) || !$kit_white){
    $paciente = (Object)\App\Pacientes::get($agenda->paciente);
}

?>
@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

<h1 class="title">PLANEJAMENTO DA ASSISTÊNCIA DE ENFERMAGEM</h1>

<div class="bloco " style="margin: 0; margin-top: 5px;">
    <h2 style="text-align: left">ANOTAÇÃO DOS SINAIS VITAIS</h2>
    <div style="margin: 5px 5px; line-height: 22px">
        <table width="100%" class="border">
            <tr>
                <th class="title">HORA</th>
                <th class="title">PA</th>
                <th class="title">P</th>
                <th class="title">FR</th>
                <th class="title">SAT %</th>
                <th class="title">DEXTRO</th>
                <th class="title" colspan="3">PUNÇÃO VENOSA ______/_______/________</th>
            </tr>
            <tr>
                <td width="9%">&nbsp;<br/><br/></td>
                <td width="9%">&nbsp;</td>
                <td width="9%">&nbsp;</td>
                <td width="9%">&nbsp;</td>
                <td width="9%">&nbsp;</td>
                <td width="9%">&nbsp;</td>
                <td width="17%">1&ordm; Punção</td>
                <td width="17%">2&ordm; Punção</td>
                <td width="17%">3&ordm; Punção</td>
            </tr>
            <tr>
                <td>&nbsp;<br/><br/></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    (&nbsp;&nbsp;&nbsp;) MSE<br/>
                    (&nbsp;&nbsp;&nbsp;) MSD
                </td>
                <td>
                    (&nbsp;&nbsp;&nbsp;) MSE<br/>
                    (&nbsp;&nbsp;&nbsp;) MSD
                </td>
                <td>
                    (&nbsp;&nbsp;&nbsp;) MSE<br/>
                    (&nbsp;&nbsp;&nbsp;) MSD
                </td>
            </tr>
            <tr>
                <td>&nbsp;<br/><br/></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td nowrap>
                    (&nbsp;&nbsp;&nbsp;) SCAP &nbsp;&nbsp;N&ordm; ____<br/>
                    (&nbsp;&nbsp;&nbsp;) JELCO N&ordm; ____<br/>
                </td>
                <td nowrap>
                    (&nbsp;&nbsp;&nbsp;) SCAP &nbsp;&nbsp;N&ordm; ____<br/>
                    (&nbsp;&nbsp;&nbsp;) JELCO N&ordm; ____<br/>
                </td>
                <td nowrap>
                    (&nbsp;&nbsp;&nbsp;) SCAP &nbsp;&nbsp;N&ordm; ____<br/>
                    (&nbsp;&nbsp;&nbsp;) JELCO N&ordm; ____<br/>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table width="100%" class="">
                        <tr>
                            <td style="text-align: center" width="16.5%"><img src="src/image/escala-dor/sem-dor.png" width="50px"/><span style="font-size: 8px !important;">Sem dor</span></td>
                            <td style="text-align: center" width="16.5%"><img src="src/image/escala-dor/dor-leve.png" width="50px"/><span style="font-size: 8px !important;">Dor leve</span></td>
                            <td style="text-align: center" width="16.5%"><img src="src/image/escala-dor/sem-dor-2.png" width="50px"/><span style="font-size: 8px !important;">Suportável</span></td>
                            <td style="text-align: center" width="16.5%"><img src="src/image/escala-dor/moderada.png" width="50px"/><span style="font-size: 8px !important;">Moderada</span></td>
                            <td style="text-align: center" width="16.5%"><img src="src/image/escala-dor/forte.png" width="50px"/><span style="font-size: 8px !important;">Forte</span></td>
                            <td style="text-align: center" width="16.5%"><img src="src/image/escala-dor/insuportavel.png" width="50px"/><span style="font-size: 8px !important;">Insuportável</span></td>
                        </tr>
                    </table>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
</div>

<div class="bloco " style="margin: 0; margin-top: 5px;">
    <h2 style="text-align: left">DIAGNÓSTICO DE ENFERMAGEM</h2>
    <div style="margin: 5px 5px; line-height: 22px">
        <table width="100%" class="">
            <tr class="line-height-16">
                <td><strong>(&nbsp;&nbsp;&nbsp;) Risco de infecção:</strong></td>
                <td colspan="2">(&nbsp;&nbsp;&nbsp;) procedimentos invasivos</td>
                <td colspan="2">(&nbsp;&nbsp;&nbsp;) cateter venoso</td>
                <td colspan="2">(&nbsp;&nbsp;&nbsp;) _____________________________</td>
            </tr>
            <tr class="line odd line-height-16">
                <td width="20%" rowspan="2"><strong>(&nbsp;&nbsp;&nbsp;) Débito cardíaco:</strong></td>
                <td colspan="1" width="13%">(&nbsp;&nbsp;&nbsp;) bradicardia</td>
                <td colspan="1" width="13%">(&nbsp;&nbsp;&nbsp;) taquicardia</td>
                <td colspan="1" width="13%">(&nbsp;&nbsp;&nbsp;) palpitações</td>
                <td colspan="1" width="13%">(&nbsp;&nbsp;&nbsp;) palidez cutânea</td>
                <td colspan="1" width="13%">(&nbsp;&nbsp;&nbsp;) sudorese</td>
                <td colspan="1" width="13%">(&nbsp;&nbsp;&nbsp;) __________</td>
            </tr>
            <tr class="line odd line-height-16">
                <td colspan="2">(&nbsp;&nbsp;&nbsp;) rebaixamento do nível de consciência</td>
                <td>(&nbsp;&nbsp;&nbsp;) agitação</td>
                <td colspan="3">(&nbsp;&nbsp;&nbsp;) ____________________________________________</td>
            </tr>
            <tr class="line-height-16">
                <td><strong>(&nbsp;&nbsp;&nbsp;) Risco de queda:</strong></td>
                <td colspan="1">(&nbsp;&nbsp;&nbsp;) transporte</td>
                <td colspan="1">(&nbsp;&nbsp;&nbsp;) déficit físico</td>
                <td colspan="1">(&nbsp;&nbsp;&nbsp;) ambiente</td>
                <td colspan="1">(&nbsp;&nbsp;&nbsp;) elevadores</td>
                <td colspan="1">(&nbsp;&nbsp;&nbsp;) escadas</td>
                <td colspan="1">(&nbsp;&nbsp;&nbsp;) piso</td>
            </tr>
            <tr class="line odd line-height-16">
                <td colspan="2"><strong>(&nbsp;&nbsp;&nbsp;) Padrão respiratório ineficaz:</strong></td>
                <td colspan="1">(&nbsp;&nbsp;&nbsp;) sialorreia</td>
                <td colspan="2">(&nbsp;&nbsp;&nbsp;) oxigenação diminuída</td>
                <td colspan="2">(&nbsp;&nbsp;&nbsp;) obstrução das vias aéreas</td>
            </tr>
            <tr class="line-height-16">
                <td colspan="1" nowrap><strong>(&nbsp;&nbsp;&nbsp;) Risco de glicemia instável:</strong></td>
                <td colspan="2">(&nbsp;&nbsp;&nbsp;) jejum prolongado</td>
                <td colspan="1">(&nbsp;&nbsp;&nbsp;) hipoglicemiante</td>
                <td colspan="1">(&nbsp;&nbsp;&nbsp;) uso de insulina</td>
                <td colspan="2">(&nbsp;&nbsp;&nbsp;) jejum para exames</td>
            </tr>
        </table>
    </div>
</div>

<div class="bloco" style="margin: 0; margin-top: 10px;">
    <h2 style="text-align: left">EVOLUÇÃO DE ENFERMAGEM</h2>
    <div style="margin: 22px 5px; line-height: 22px">
        <hr/>
        @for($i = 0; $i < 14; $i++)
            <br/>
            <hr/>
        @endfor
    </div>
</div>


<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>

<div style='page-break-before:always;'>
    <br/>
    <br/>
    <div class="bloco" style="margin: 0; margin-top: 10px;">
        <h2 style="text-align: left">ANOTAÇÃO DE ENFERMAGEM</h2>
        <div style="margin: 22px 5px; line-height: 22px">
            <hr/>
            @for($i = 0; $i < 30; $i++)
                <br/>
                <hr/>
            @endfor
        </div>
    </div>
    <br/>
    <p>Destino do paciente:</p>
    <table width="100%" class="">
        <tr>
            <td>(&nbsp;&nbsp;&nbsp;) UBS</td>
            <td>(&nbsp;&nbsp;&nbsp;) AMA</td>
            <td nowrap>(&nbsp;&nbsp;&nbsp;) Alta após realizar exame</td>
            <td>(&nbsp;&nbsp;&nbsp;) Remarcação</td>
            <td>(&nbsp;&nbsp;&nbsp;) SAMU</td>
            <td>(&nbsp;&nbsp;&nbsp;) Alta</td>
            <td>(&nbsp;&nbsp;&nbsp;) Residência</td>
            <td width="*" nowrap>(&nbsp;&nbsp;&nbsp;) ______________________________</td>
        </tr>
    </table>

    <div style="margin-top: 70px">
        @include('elements.layout.kit.assinaturas.assinatura-carimbo-enfermagem')
    </div>

    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>

</div>