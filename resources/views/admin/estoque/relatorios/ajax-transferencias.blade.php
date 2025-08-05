<table class="table table-striped table-responsive table-bordered  bg-light ">
    <thead>
        <tr role="row">
            <th>Produto</th>
            <th>Arena</th>
            <th>Lote</th>
            <th>Responsável</th>
            <th>Quantidade</th>
            <th>Data Hora</th>
            <th>Usuário que recebeu</th>
            <th>Data de recebimento</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach ($transferencias as $transferencia)
        <tr>
            <td>{{ $transferencia['nome_produto'] }}</td>
            <td>{{ $transferencia['nome_arena'] }}</td>
            <td>{{ $transferencia['numero_lote'] }}</td>
            <td>{{ $transferencia['usuario'] }}</td>
            <td>{{ $transferencia['quantidade'] }}</td>
            <td>{{ date('d/m/Y H:i', strtotime($transferencia['data_transferencia'])) }}</td>
            <td>{{ $transferencia['usuario_recebido'] }}</td>
            <td>{{ !empty($transferencia['data_recebido']) ? date('d/m/Y H:i', strtotime($transferencia['data_recebido'])) : '' }}</td>
        </tr>  
        @endforeach
    </tbody>
</table>