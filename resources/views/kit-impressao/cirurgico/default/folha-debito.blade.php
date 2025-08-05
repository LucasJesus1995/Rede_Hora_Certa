<?php
if (!isset($kit_white) || !$kit_white) {
    $paciente = (Object)\App\Pacientes::get($agenda->paciente);
} else {

}
?>
<div style='page-break-before:always;' class="kit-impressao">
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

    <h1 class="title">FOLHA DE DÉBITO</h1>

    <div class="bloco " style="margin: 0; margin-top: 10px;">

        <table width="100%" class="" style="margin: 2px">
            @if(!in_array($agenda->linha_cuidado, [9]))
                <tr class="line-height-18">
                    <td width="50%">
                        <strong>Horário Início da Cirurgia: </strong> ________:________
                    </td>
                    <td width="50%">
                        <strong>Horário Final da Cirurgia: </strong> ________:________
                    </td>
                </tr>
            @endif
            <tr class="line-height-18">
                <td width="50%">
                    <strong>Cirurgião: </strong> ______________________________________________
                </td>
                <td width="50%">
                    <strong>Instrumentador (a): </strong> __________________________________________
                </td>
            </tr>
            @if(!in_array($agenda->linha_cuidado, [9]))
                <tr class="line-height-18">
                    <td width="50%">
                        <strong>Anestesia: </strong> ______________________________________________
                    </td>
                    <td width="50%">
                        <strong>Anestesista: </strong> ________________________________________________
                    </td>
                </tr>
            @endif
            <tr class="line-height-18">
                <td width="50%">
                    <strong>Circulante: </strong> ______________________________________________
                </td>
                <td width="50%">
                    <strong>Enfermeira: </strong> _________________________________________________
                </td>
            </tr>
        </table>

        <hr/>

        @if(in_array($agenda->linha_cuidado, [9]))
            <table width="100%" class="" style="margin: 5px 7px">
                <tr>
                    <th class="title border" style="text-align: left">TRATAMENTO ESCLEROSANTE DE VARIZES</th>
                    <td class="title border" width="15%">(&nbsp;&nbsp;&nbsp;&nbsp;) BILATERAL</td>
                    <td class="title border" width="15%">(&nbsp;&nbsp;&nbsp;&nbsp;) UNILATERAL</td>
                </tr>
            </table>
        @endif

        @if(in_array($agenda->linha_cuidado, [45, 19]))
            <table width="100%" class="" style="margin: 5px 7px">
                <tr>
                    <th class="title border" style="text-align: left">TRATAMENTO CIRURGICO DE OFTMALOGIA</th>
                    <td class="title border" width="15%">(&nbsp;&nbsp;&nbsp;&nbsp;) DIREITO</td>
                    <td class="title border" width="15%">(&nbsp;&nbsp;&nbsp;&nbsp;) ESQUERDO</td>
                </tr>
            </table>
        @endif

        <table width="100%" class="" style="margin: 5px">
            <tr>
                <td width="45%">
                    <table width="100%" class="border" cellspacing="0" cellpadding="0">
                        <tr>
                            <th class="title">MATERIAL</th>
                            <th class="title" width="5%">QTDE</th>
                        </tr>
                        <tbody class="">
                        @foreach(\App\Http\Helpers\Cirurgico\FolhaDebitoHelpers::getFolhaDebitoMaterial($agenda->linha_cuidado, $sub_especialidade) AS $row)
                            <tr class="{!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                                <td style="font-size: 7px !important; padding: 0;">{!! App\Http\Helpers\Util::String2DB($row) !!}</td>
                                <td style="font-size: 7px !important; padding: 0; text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </td>
                <td width="10%">&nbsp;</td>
                <td width="45%">
                    <table width="100%" class="border">
                        <tr>
                            <th class="title">CENTRAL MATERIAL</th>
                            <th class="title" width="5%">QTDE</th>
                        </tr>
                        <tbody class="">
                        @foreach(\App\Http\Helpers\Cirurgico\FolhaDebitoHelpers::getFolhaDebitoCentralMaterial($agenda->linha_cuidado, $sub_especialidade) AS $row)
                            <tr class="{!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                                <td style="font-size: 7px !important; padding: 0;">{!! App\Http\Helpers\Util::String2DB($row) !!}</td>
                                <td style="font-size: 7px !important; padding: 0; text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <table width="100%" class="border" style="margin-top: 10px">
                        <tr>
                            <th class="title">EQUIPAMENTO</th>
                            <th class="title" width="5%">QTDE</th>
                        </tr>
                        <tbody class="">
                        @foreach(\App\Http\Helpers\Cirurgico\FolhaDebitoHelpers::getFolhaDebitoEquipamento($agenda->linha_cuidado, $sub_especialidade) AS $row)
                            <tr class="{!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                                <td style="font-size: 7px !important; padding: 0;">{!! App\Http\Helpers\Util::String2DB($row) !!}</td>
                                <td style="font-size: 7px !important; padding: 0; text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <table width="100%" class="border" style="margin-top: 10px">
                        <tr>
                            <th class="title">SORO</th>
                            <th class="title" width="5%">QTDE</th>
                        </tr>
                        <tbody class="">
                        @foreach(\App\Http\Helpers\Cirurgico\FolhaDebitoHelpers::getFolhaDebitoSoro($agenda->linha_cuidado, $sub_especialidade) AS $row)
                            <tr class="{!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                                <td style="font-size: 7px !important; padding: 0;">{!! App\Http\Helpers\Util::String2DB($row) !!}</td>
                                <td style="font-size: 7px !important; padding: 0; text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>

        </table>


        <div style="margin:  5px; position: absolute; bottom: 5px">
            Preenchido por: _____________________________________________________________________________________________________________________
        </div>
    </div>


    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>
