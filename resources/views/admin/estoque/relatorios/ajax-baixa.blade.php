<table class="table table-striped table-responsive table-bordered  bg-light ">
    <thead>
        <tr role="row">
            <th>Produto</th>
            <th>Arena</th>
            <th>Lote</th>
            <th>Tipo Baixa</th>
            <th>Tipo Consumo</th>
            <th>Responsável</th>
            <th>Quantidade</th>
            <th>Data Hora</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($baixas as $baixa)
        <tr>
            <td>{{ $baixa['nome_produto'] }}</td>
            <td>{{ $baixa['nome_arena'] }}</td>
            <td>{{ $baixa['numero_lote'] }}</td>
            <td>{{ $tipo_baixa[$baixa['tipo_baixa']] }}</td>
            <td>{{ $tipo_consumo[$baixa['tipo_consumo']] }}</td>
            <td>{{ $baixa['usuario'] }}</td>
            <td>{{ $baixa['quantidade'] }}</td>
            <td>{{ date('d/m/Y H:i', strtotime($baixa['data_baixa'])) }}</td>
        </tr>
          
        @endforeach
    </tbody>
</table>