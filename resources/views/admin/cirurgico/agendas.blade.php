<h3 class="text-center">{{$paciente->nome}}</h3>
<table class="table table-hover">
    <thead>
        <tr>
            {{-- <th></th> --}}
            <th>Agenda</th>
            <th>Data Atendimento</th>
            <th>Unidade</th>
            <th>Especialidade</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($agendas as $agenda)
        <tr>
            <td>{{ $agenda->id }}</td>
            <td>{{ date('d/m/Y', strtotime($agenda->data)) }}</td>
            <td>{{ $agenda->arena }}</td>
            <td>{{ $agenda->linha_cuidado }}</td>
            <td>{{ \App\Http\Helpers\Util::StatusAgenda($agenda->status) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>