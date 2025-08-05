@if(!empty($relatorio))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <?php
    $ln = 3;
    ?>
    @if(!empty($relatorio))
        <table class="table table-striped table-responsive table-bordered  bg-light">
            <tr>
                <th>AGENDAS</th>
                <th>DATA</th>
                <th>HORA</th>
                <th>ATENDIMENTOS</th>
                <th>UNIDADES</th>
                <th>ESPECIALIDADES</th>
                <th>MÉDICOS</th>
                <th>CRM</th>
                <th>PROCEDIMENTOS</th>
                <th>SUS</th>
                <th>QUANTIDADE</th>
            </tr>
            @foreach($relatorio AS $row)
                <tr>
                    <td>{!! $row->agenda !!}</td>
                    <td>{!! \App\Http\Helpers\Util::DBTimestamp2UserDate($row->data) !!}</td>
                    <td>{!! \App\Http\Helpers\Util::DBTimestamp2UserTime2($row->data) !!}</td>
                    <td>{!! $row->atendimento !!}</td>
                    <td>{!! $row->arena !!}</td>
                    <td>{!! $row->linha_cuidado !!}</td>
                    <td>{!! $row->medico !!}</td>
                    <td>{!! $row->crm !!}</td>
                    <td>{!! $row->procedimento !!}</td>
                    <td>{!! \App\Http\Helpers\Mask::ProcedimentoSUS($row->sus) !!}</td>
                    <td>{!! $row->quantidade !!}</td>
                </tr>
            @endforeach
        </table>
    @endif
    </html>
@endif