<html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/biopsia.css">

    <table>
        <tr>
            <th>UNIDADE</th>
            <th>ESPECIALIDADE</th>
            <th>ABERTURA</th>
            <th>TOTAL</th>
        </tr>
        @if(!empty($relatorio))
            @foreach($relatorio AS $key => $row)
                <tr class="line {!! ($key % 2) ? 'odd' : 'even' !!}">

                    <td>{!! $row->arena_nome !!}</td>
                    <td>{!! $row->linha_cuidado_nome !!}</td>
                    <td>{!! $row->periodo !!}:00:00</td>
                    <td>{!! $row->total !!}</td>
                </tr>
            @endforeach
        @endif
    </table>

</html>