@if(!empty($data))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <table width="100%" border="1">
        <tr>
            <th width="13">Código</th>
            <th width="50">Nome</th>
            <th width="40">E-mail</th>
            <th width="30">Perfil</th>
            <th width="10">Ativo</th>
            <th width="20">Atualização</th>
            <th width="20">Criação</th>

        </tr>
        @foreach($data AS $row)
            <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                <td class="left">{!! $row->id !!}</td>
                <td class="left">{!! $row->name !!}</td>
                <td class="left">{!! $row->email !!}</td>
                <td class="left">{!! $row->perfil !!}</td>
                <td class="center">{!! $row->active !!}</td>
                <td class="center">{!! $row->updated_at !!}</td>
                <td class="center">{!! $row->created_at !!}</td>
            </tr>
        @endforeach
    </table>

    </html>
@endif