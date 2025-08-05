<html>
<link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/biopsia.css">

<table>
    <tr>
        <th>NOME</th>
        <th>CNS</th>
        <th>CRO</th>
        <th>CPF</th>
        <th>ATIVO</th>
        <th>CBO(s)</th>
        <th>UNIDADE(s)</th>
        <th>LINHA(s) CUIDADO</th>
        <th>LOTE(s)</th>
    </tr>
    @foreach($relatorio AS $key => $row)
        <?php
        $cbo = \App\ProfissionaisCbo::getCboByProfissional($row['id'])->lists('nome')->toArray();
        $arenas = \App\ProfissionaisArenas::getArenasByProfissional($row['id'])->lists('nome')->toArray();
        $linhaCuidado = \App\ProfissionaisLinhaCuidado::getLinhasCuidadoByProfissional($row['id'])->lists('nome')->toArray();
        $lote = \App\LoteProfissional::getLoteByProfissional($row['id'])->lists('nome')->toArray();
        ?>
        <tr class="line {!! ($key % 2) ? 'odd' : 'even' !!}">
            <td>{{ $row['nome'] }}</td>
            <td>{{ $row['cns'] }}&nbsp;</td>
            <td>{{ $row['cro'] }}&nbsp;</td>
            <td>{{ \App\Http\Helpers\Mask::Cpf($row['cpf']) }}&nbsp;</td>
            <td>{{ \App\Http\Helpers\Util::Ativo($row['ativo']) }}&nbsp;</td>
            <td>
                @if(!empty($cbo))
                    {!! implode("<br />", $cbo) !!}
                @endif
            </td>
            <td>
                @if(!empty($arenas))
                    {!! implode("<br />", $arenas) !!}
                @endif
            </td>
            <td>
                @if(!empty($linhaCuidado))
                    {!! implode("<br />", $linhaCuidado) !!}
                @endif
            </td>
            <td>
                @if(!empty($lote))
                    {!! implode("<br />", $lote) !!}
                @endif
            </td>

        </tr>
    @endforeach
</table>

</html>

<?php // die;?>