<html>
<link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/dark.css">

<table>
    <tr>
        <th width="20">Contrato</th>
        <th width="12">Data</th>
        <th width="10">Total</th>
        <th width="90">Unidades</th>
        <th width="40">Linhas de Cuidado</th>
        <th width="16">Código</th>
        <th width="12">CRM</th>
        <th width="40">Profissional (Médico)</th>
        <th width="90">Procedimentos</th>
        <th width="20">Status</th>
    </tr>
    <?php
    $i_line = 2;
    $status = \App\Http\Helpers\Util::StatusAgenda();
    ?>
    @if(!empty($relatorio))
        @foreach($relatorio AS $row)
            <tr class="line {!! ($i_line % 2) ? 'odd' : 'even' !!}">
                <td>{!! $row->lote !!}</td>
                <td>{!! $row->data_exame !!}</td>
                <td>{!! $row->total !!}</td>
                <td>{!! $row->arena !!}</td>
                <td>{!! $row->linha_cuidado !!}</td>
                <td>{!! \App\Http\Helpers\Mask::CodigoProcedimento($row->cod_procedimento) !!}</td>
                <td>{!! $row->crm !!}</td>
                <td>{!! $row->medico !!}</td>
                <td>{!! $row->procedimento !!}</td>
                <td>{!! (array_key_exists($row->status, $status)) ? $status[$row->status] : null !!}</td>
            </tr>
            <?php $i_line++; ?>
        @endforeach
    @endif
</table>

</html>