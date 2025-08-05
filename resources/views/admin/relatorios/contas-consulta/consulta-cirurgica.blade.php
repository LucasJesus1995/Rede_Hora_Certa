@if(!empty($relatorio))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    @if(!empty($relatorio))
        <table width="100%" border="1">
            <tr>
                <th width="60">ARENA</th>
                <th width="25">DATA ATENDIMENTO</th>
                <th width="60">MEDICO</th>
                <th width="40">ESPECIALIDADE</th>
                <th width="40">TIPO ATENDIMENTO</th>
                <th width="40">CONDUTA PRINCIPAL</th>
                <th width="15">TOTAL</th>
                <th width="20">VALOR UNITARIO</th>
                <th width="20">VALOR REPASSE</th>
            </tr>
            <?php
            $ln = 2;
            ?>
            @foreach($relatorio AS $row)
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td class="">{!! $row->arena!!}</td>
                    <td class="center">{!! $row->data_atendimento!!}</td>
                    <td>{!! $row->medico!!}</td>
                    <td>{!! $row->especialidade!!}</td>
                    <td>{!! $row->tipo_atendimento!!}</td>
                    <td>{!! $row->conduta_principal!!}</td>
                    <td>{!! $row->total!!}</td>
                    <td>{!! $row->valor_unitario!!}</td>
                    <td></td>
                </tr>
                <?php $ln++; ?>
            @endforeach
        </table>
    @endif

    </html>
@endif