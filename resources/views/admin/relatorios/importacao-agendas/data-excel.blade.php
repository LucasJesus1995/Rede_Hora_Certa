@if(!empty($relatorio))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">
    @if(!empty($relatorio))
        <table class="table table-striped table-responsive table-bordered  bg-light">
            <tr>
                <th width="25">Data importação</th>
                <th width="25">Data agendamento</th>
                <th width="100">Unidade</th>
                <th width="40">Especialidade</th>
                <th width="25">Tipo agenda</th>
                <th width="30">Procedimento</th>
                <th width="45">Usuário</th>
                <th width="30">Pacientes importados</th>
                <th width="45">Pacientes importações (Sucesso)</th>
                <th width="45">Pacientes importações (Falhas)</th>
            </tr>

            @foreach($relatorio AS $row)
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td class="center">{!! $row['data_importacao'] !!}</td>
                    <td class="center">{!! $row['data'] !!}</td>
                    <td>{!! $row['arena'] !!}</td>
                    <td>{!! $row['linha_cuidado'] !!}</td>
                    <td>{!! $row['tipo'] !!}</td>
                    <td>{!! $row['tipo_atendimento'] !!}</td>
                    <td>{!! $row['user'] !!}</td>
                    <td class="center">{!! $row['registro'] !!}</td>
                    <td class="center">{!! $row['importacao'] !!}</td>
                    <td class="center">{!! $row['falhas'] !!}</td>
                </tr>
            @endforeach
        </table>
    @endif
    </html>
@endif
