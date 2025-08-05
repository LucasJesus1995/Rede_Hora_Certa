<table class="table table-striped table-responsive table-bordered  bg-light ">
    <thead>
        <tr role="row">
            <th>Produto</th>
            <th>Lote</th>
            <th>Responsável</th>
            <th>Quantidade</th>
            <th>Data Hora</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produtos as $produto)
        <tr>

            <td>{{ $produto['nome_produto'] }}</td>
            <td>{{ $produto['numero_lote'] }}</td>
            <td>{{ $produto['usuario'] }}</td>
            <td>{{ $produto['quantidade'] }}</td>
            <td>{{ date('d/m/Y H:i', strtotime($produto['data_entrada'])) }}</td>
        </tr>   
        @endforeach
    </tbody>
</table>