
<table class="table table-striped table-responsive table-bordered  bg-light ">
    <thead>
        <tr role="row">
            <th>Produto</th>
            <th>Arena</th>
            <th>Lote</th>
            <th>Quantidade</th>
            <th>Data Vencimento</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produtos as $produto)
        <tr>
            <td>{{ $produto['nome_produto'] }}</td>
            <td>{{ empty($produto['nome_arena']) ? 'LUTÉCIA' : $produto['nome_arena'] }}</td>
            <td>{{ $produto['codigo'] }}</td>
            <td>{{ $produto['quantidade'] }}</td>
            <td>{{ date('d/m/Y', strtotime($produto['vencimento'])) }}</td>
        </tr>  
        @endforeach
    </tbody>
</table>