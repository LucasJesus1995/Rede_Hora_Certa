@if($pdf)
    <style>
        td{
            font-size: 25px;
            text-align: center;
        }
        th{
            font-size: 25px;
            text-align: center;
        }
    </style>
    <h2 style="text-align: center">DESTINO: {{ $_arena->nome }}</h2>
    <table style="width: 100%" border="1" cellspacing="0" cellpadding="0">
@else
    <table class="table table-striped table-responsive table-bordered  bg-light " >
@endif
    <thead>
        <tr role="row">
            <th>Produto</th>
            <th>Lote</th>
            <th>Quantidade</th>
            <th>Responsável</th>
            <th>Data Envio</th>
            <th>Data Recebimento</th>
            <th>Recebido por</th>
            <th></th>
        </tr>
    </thead>
    <?php 
    $i = 0;
    ?>
    <tbody>
        @foreach($transferencias AS $row)
            @if($pdf)
                <tr style="{{ ($i % 2 == 0 ? 'background-color: #ccc' : '') }}">
            @else
                <tr>
            @endif
                <td>{{$row->produto}}</td>
                <td>{{$row->lote}}</td>
                <td>{{$row->quantidade}}</td>
                <td>{{$row->resp}}</td>
                <td>{{date('d/m/Y H:i', strtotime($row->created_at))}}</td>
                <td>{{ (!empty($row->data_recebido) ? date('d/m/Y H:i', strtotime($row->data_recebido)) : '') }}</td>
                <td>{{ $row->name }}</td>
                <td>@if($row->recebido == 0)
                        <button onclick="confirmarRecebimento({{ $row->id }})">Confirmar</button>
                    @endif
                </td>
            </tr>
            <?php
            $i++;
            ?>  
        @endforeach
    </tbody>
</table>
