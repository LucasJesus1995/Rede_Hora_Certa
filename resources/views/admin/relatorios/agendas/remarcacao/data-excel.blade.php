@if(!empty($relatorio))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    @if(!empty($relatorio))
        <table width="100%" border="1">
            <tr>
                <th width="13">Agenda</th>
                <th width="20">Data</th>
                <th width="60">Paciente</th>
                <th width="22">CNS</th>
                <th width="60">Unidade</th>
                <th width="40">Especialidade</th>
                <th width="22">Agenda (Remarcada)</th>
                <th width="22">Data (Remarcada)</th>
                <th width="30">Equipamento</th>
            </tr>
            <?php
            $ln = 2;
            ?>
            @foreach($relatorio AS $row)
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td class="center">{!! $row->id!!}</td>
                    <td class="center">{!! \App\Http\Helpers\Util::DBTimestamp2User2($row->data)!!}</td>
                    <td>{!! $row->paciente!!}</td>
                    <td class="center">{!! $row->cns!!}</td>
                    <td>{!! $row->arena!!}</td>
                    <td>{!! $row->linha_cuidado!!}</td>
                    <td class="center">{!! $row->id_remarcada!!}</td>
                    <td class="center">{!! \App\Http\Helpers\Util::DBTimestamp2User2($row->data_remarcada)!!}</td>
                    <td>{!! $row->equipamento!!}</td>
                </tr>
                <?php $ln++; ?>
            @endforeach
        </table>
    @endif

    </html>
@endif