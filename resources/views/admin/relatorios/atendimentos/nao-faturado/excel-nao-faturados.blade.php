@if(!empty($atendimentos))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <table width="100%" border="1">
        <tr>
            <th width="15">Agenda</th>
            <th width="15">Data</th>
            <th width="60">Paciente</th>
            <th width="60">Unidade</th>
            <th width="60">Especialidades</th>
            <th width="90">Procedimento</th>
            <th width="60">Médico</th>
        </tr>
        @foreach($atendimentos AS $row)
            <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                <td class="center">{!! $row->id !!}</td>
                <td class="center">{!! \App\Http\Helpers\Util::DBTimestamp2UserDate($row->data) !!}</td>
                <td>{!! $row->paciente !!}</td>
                <td>{!! $row->nome !!}</td>
                <td>{!! $row->linha_cuidado !!}</td>
                <td>{!! $row->procedimento_nome !!}</td>
                <td>{!! $row->medico !!}</td>
            </tr>
        @endforeach
    </table>
    </html>
@endif
