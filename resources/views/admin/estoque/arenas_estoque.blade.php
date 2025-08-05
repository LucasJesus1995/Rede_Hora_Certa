<table class="table table-striped table-responsive table-bordered  bg-light " >
    <thead>
        <tr role="row">
            <th>Produto</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
        @foreach($quantidades AS $row)
            <tr class="grid-status-{{$row->ativo}}">
                <td>{{$row->nome}}</td>
                <td>{{$row->quantidade}}</td>               
            </tr>
        @endforeach
    </tbody>
</table>
<button class="btn btn-success" type="button" onclick="exportarExcel()">Exportar</button>