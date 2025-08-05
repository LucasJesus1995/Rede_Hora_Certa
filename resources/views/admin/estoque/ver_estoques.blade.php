<h3 class="text-center">PRODUTO: {{strtoupper($produto->nome) }}</h3>
<h3 class="text-center">Em estoque</h3>
<table class="table table-striped table-responsive table-bordered  bg-light " >
    <thead>
        <tr role="row">
            <th>Local</th>
            <th>Lote</th>
            <th>Vencimento</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
        @foreach($quantidades_central as $quantidade_central)
        <tr>
            <td>LUTÉCIA</td>
            <td>{{$quantidade_central->codigo}}</td>               
            <td>{{ date('d/m/Y', strtotime($quantidade_central->vencimento)) }}</td>               
            <td>{{$quantidade_central->quantidade}}</td>               
        </tr>
        @endforeach
        @foreach($quantidades AS $row)
            <tr class="grid-status-{{$row->ativo}}">
                <td>{{$row->nome}}</td>
                <td>{{$row->codigo}}</td>
                <td>{{ date('d/m/Y', strtotime($row->vencimento)) }}</td>               
                <td>{{$row->quantidade}}</td>               
            </tr>
        @endforeach
    </tbody>
</table>
<h3 class="text-center">Em transferência</h3>
<table class="table table-striped table-responsive table-bordered  bg-light " >
    <thead>
        <tr role="row">
            <th>Destino</th>
            <th>Lote</th>
            <th>Vencimento</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tranferencias AS $row)
            <tr class="grid-status-{{$row->ativo}}">
                <td>{{$row->nome}}</td>
                <td>{{$row->codigo}}</td>
                <td>{{ date('d/m/Y', strtotime($row->vencimento)) }}</td>               
                <td>{{$row->quantidade}}</td>               
            </tr>
        @endforeach
    </tbody>
</table>