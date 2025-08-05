@if(!empty($atendimentos))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <table width="100%" border="1">
        <tr>
            <th colspan="2">DATAS</th>
            <td width="5"></td>

            <th colspan="2">UNIDADES</th>
            <td width="5"></td>

            <th colspan="2">ESPECIALIDADES</th>
            <td width="5"></td>

            <th colspan="2">PROCEDIMENTOS</th>
            <td width="5"></td>

            <th colspan="2">PROFISSIONAIS</th>
            <td width="5"></td>
        </tr>
        <tr>
            <th width="15">Data</th>
            <th width="10">Qtd.</th>
            <td></td>

            <th width="60">Arenas</th>
            <th width="10">Qtd.</th>
            <td></td>

            <th width="45">Especialidade</th>
            <th width="10">Qtd.</th>
            <td></td>

            <th width="70">Procedimentos</th>
            <th width="10">Qtd.</th>
            <td></td>

            <th width="45">Médicos</th>
            <th width="10">Qtd.</th>
        </tr>
        <?php
        $ln = 3;
        ?>
        @for($i = 0; $i< $linhas; $i++)
            <?php
                $zebra =  (@$zebra == "even") ? "odd" : "even";
            ?>

            <tr class="line ">
                @if(!empty($atendimentos['data'][$i]))
                    <td class="center {!! $zebra !!}">{!! $atendimentos['data'][$i] !!}</td>
                    <td class="right {!! $zebra !!}">=COUNTIF('NÃO FATURADOS'!B:B,A{!! $ln !!})</td>
                @else
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                @endif
                    <td class="no-border"></td>

                @if(!empty($atendimentos['arena'][$i]))
                    <td class="left {!! $zebra !!}">{!! $atendimentos['arena'][$i] !!}</td>
                    <td class="right {!! $zebra !!}">=COUNTIF('NÃO FATURADOS'!C:C,D{!! $ln !!})</td>
                @else
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                @endif
                    <td class="no-border"></td>

                @if(!empty($atendimentos['linha_cuidado'][$i]))
                    <td class="left {!! $zebra !!}">{!! $atendimentos['linha_cuidado'][$i] !!}</td>
                    <td class="right {!! $zebra !!}">=COUNTIF('NÃO FATURADOS'!D:D,G{!! $ln !!})</td>
                @else
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                @endif
                    <td class="no-border"></td>

                @if(!empty($atendimentos['procedimento_nome'][$i]))
                    <td class="left {!! $zebra !!}">{!! $atendimentos['procedimento_nome'][$i] !!}</td>
                    <td class="right {!! $zebra !!}">=COUNTIF('NÃO FATURADOS'!E:E,J{!! $ln !!})</td>
                @else
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                @endif
                    <td class="no-border"></td>

                @if(!empty($atendimentos['medico'][$i]))
                    <td class="left {!! $zebra !!}">{!! $atendimentos['medico'][$i] !!}</td>
                    <td class="right {!! $zebra !!}">=COUNTIF('NÃO FATURADOS'!F:F,M{!! $ln !!})</td>
                @else
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                @endif

            </tr>
            <?php
            $ln++;
            ?>
        @endfor
    </table>

    </html>
@endif
<?php
//die;
?>