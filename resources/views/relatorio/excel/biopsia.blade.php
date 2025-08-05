<html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/biopsia.css">
    <table>
        @foreach($relatorio AS $_key => $_data)
            <tr bgcolor="#f5f5dc">
                <th rowspan="2" width="20">DATA</th>
                <th colspan="5">PACIENTE</th>
                <th rowspan="2" width="40">UBS</th>
                <th colspan="6">ATENDIMENTO</th>
                <th rowspan="2" width="50">DR. (A)</th>
                <th colspan="3">BIOPSIA</th>
            </tr>
            <tr bgcolor="#f5f5dc">
                <th></th>
                <th width="50">NOME</th>
                <th width="20">SUS</th>
                <th width="20">TELEFONE</th>
                <th width="25">NASCIMENTO (IDADE)</th>
                <th width="15">ID</th>
                <th></th>
                <th width="15">ID</th>
                <th width="60">UNIDADE</th>
                <th width="30">ESPECIALIDADE</th>
                <th width="70">PROCEDIMENTO</th>
                <th width="15">LAUDO</th>
                <th width="15">ENVIADO</th>
                <th></th>
                <th width="30">RESULTADO</th>
                <th width="70">ANALISE</th>
                <th width="20">STATUS</th>
            </tr>
            @foreach($_data AS $key => $row)
                <?php
                    $resultado = strtoupper(\App\Http\Helpers\Util::getLaudoResultados($row['resultado']));
                    $resultado = !is_array($resultado) ? $resultado : null;

                    $status = \App\Http\Helpers\Util::statusLaudo($row['status_biopsia']);
                    $status = !is_array($status) ? $status : null;
                ?>
                <tr class="line {!! ($key % 2) ? 'odd' : 'even' !!}">
                    <td>{{ \App\Http\Helpers\Util::DBTimestamp2User2($row['data']) }}</td>

                    <td>{{ $row['nome'] }}</td>
                    <td align="center">{{ $row['cns'] }}</td>
                    <td align="left">{{ \App\Http\Helpers\Mask::telefone(!empty($row['celular']) ? $row['celular'] : !empty($row['telefone_comercial']) ? $row['telefone_comercial'] : $row['telefone_residencial']) }}</td>
                    <td align="left">{{ \App\Http\Helpers\Util::DB2Users($row['nascimento']) }} ({!! \App\Http\Helpers\Util::calculaIdade($row['nascimento'], \App\Http\Helpers\Util::DB2Users($_key)) !!} anos)</td>
                    <td align="center">{{ $row['paciente_id'] }}</td>

                    <td>{{ $row['estabelecimento'] }}</td>

                    <td align="center">{{ $row['atendimento'] }}</td>
                    <td>{{ trim($row['arena_nome']) }}</td>
                    <td>{{ $row['linha_cuidado'] }}</td>
                    <td>{{ $row['procedimento'] }}</td>
                    <td align="center">{{ $row['laudo'] }}</td>
                    <td align="center"></td>

                    <td>{{ $row['profissional'] }}</td>

                    <td align="center">{{ $resultado }}</td>
                    <td>{{ $row['biopsia'] }}</td>
                    <td>{{ $status }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
            </tr>
        @endforeach
    </table>
</html>