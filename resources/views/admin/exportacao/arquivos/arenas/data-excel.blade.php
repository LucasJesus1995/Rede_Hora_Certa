@if(!empty($data))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <table width="100%" border="1">
        <tr>
            <th width="13">Código</th>
            <th width="70">Nome</th>
            <th width="40">Alias</th>
            <th width="40">Endereço</th>
            <th width="14">Numero</th>
            <th width="40">Complemento</th>
            <th width="40">Bairro</th>
            <th width="20">CNES</th>
            <th width="15">Estado</th>
            <th width="15">Telefone</th>
            <th width="12">Celular</th>
            <th width="60">Descrição</th>
            <th width="15">Longitude</th>
            <th width="15">Latitude</th>
            <th width="10">Ativo</th>
            <th width="20">Atualização</th>
            <th width="20">Criação</th>

        </tr>
        @foreach($data AS $row)
            <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                <td class="left">{!! $row->id !!}</td>
                <td class="left">{!! $row->nome !!}</td>
                <td class="left">{!! $row->alias !!}</td>
                <td class="left">{!! $row->endereco !!}</td>
                <td class="left">{!! $row->numero !!}</td>
                <td class="left">{!! $row->complemento !!}</td>
                <td class="left">{!! $row->bairro !!}</td>
                <td class="left">{!! $row->cnes !!}</td>
                <td class="left">{!! $row->estado !!}</td>
                <td class="left">{!! $row->telefone !!}</td>
                <td class="left">{!! $row->celular !!}</td>
                <td class="left">{!! $row->descricao !!}</td>
                <td class="left">{!! $row->longitude !!}</td>
                <td class="left">{!! $row->latitude !!}</td>
                <td class="center">{!! $row->ativo !!}</td>
                <td class="center">{!! $row->updated_at !!}</td>
                <td class="center">{!! $row->created_at !!}</td>
            </tr>
        @endforeach
    </table>

    </html>
@endif