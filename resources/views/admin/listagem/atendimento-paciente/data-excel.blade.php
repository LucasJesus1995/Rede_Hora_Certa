<?php
    $_status = \App\Http\Helpers\Util::StatusAgenda();
?>
@if(!empty($atendimentos))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">
    
    <table width="100%" border="1">
        <tr>
            <th width="50">Unidade</th>
            <th width="30">Especialidade</th>
            <th width="13">Agenda</th>
            <th width="40">Equipamento</th>
            <th width="20">Status</th>
            <th width="13">Data</th>
            <th width="11">Hora</th>
            <th width="45">Paciente</th>
            <th width="21">CNS</th>
            <th width="16">CPF</th>

        </tr>
        @foreach($atendimentos AS $row)
            <?php
                $data = explode(" ", $row->data);

                $status = array_key_exists($row->status, $_status) ? $_status[$row->status] : null;
            ?>
            <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                <td class="left">{!! $row->arena !!}</td>
                <td class="left">{!! $row->linha_cuidado !!}</td>
                <td class="center">{!! $row->id !!}</td>
                <td class="left">{!! \App\Http\Helpers\Util::getArenaEquipamentoNome($row->equipamento) !!}</td>
                <td class="left">{!! $status !!}</td>
                <td class="center">{!! \App\Http\Helpers\Util::DB2User($data[0]) !!}</td>
                <td class="center">{!! $data[1] !!}</td>
                <td class="left">{!! $row->nome !!}</td>
                <td class="center">{!! $row->cns !!}</td>
                <td class="center">{!! \App\Http\Helpers\Mask::Cpf($row->cpf) !!}</td>
            </tr>
        @endforeach
    </table>
    
    </html>
@endif