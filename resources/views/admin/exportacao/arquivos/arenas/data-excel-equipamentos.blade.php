@if(!empty($data))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <table width="100%" border="1">
        <tr>
            <th width="70">Arenas</th>
            <th width="50">Equipamentos</th>
            <th width="10">Ativo</th>
            <th width="20">Atualização</th>
            <th width="20">Criação</th>

        </tr>
        @foreach($data AS $row)
            <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                <td class="left">{!! $row->nome !!}</td>
                <td class="left">{!! $row->equipamento !!}</td>
                <td class="center">{!! $row->ativo !!}</td>
                <td class="center">{!! $row->updated_at !!}</td>
                <td class="center">{!! $row->created_at !!}</td>
            </tr>
        @endforeach
    </table>

    </html>
@endif