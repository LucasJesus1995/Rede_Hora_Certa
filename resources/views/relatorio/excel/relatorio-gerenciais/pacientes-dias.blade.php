
<html>
<link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/biopsia.css">

<table>
    <tr>
        <th>Agendamento</th>
        <th>Unidade</th>
        <th>Especialidade</th>
        <th>Médico</th>
        <th>Paciente</th>
    </tr>
    @foreach($relatorio AS $key => $row)
        <tr class="line {!! ($key % 2) ? 'odd' : 'even' !!}">
            <td>{{ \App\Http\Helpers\Util::DBTimestamp2User2($row['agendas_data']) }}</td>
            <td>{{ $row['arenas_nome'] }}&nbsp;</td>
            <td>{{ $row['linha_cuidado'] }}&nbsp;</td>
            <td>{{ $row['medico'] }}&nbsp;</td>
            <td>{{ $row['paciente_nome'] }}&nbsp;</td>
        </tr>
    @endforeach
</table>

</html>
