@if(!empty($data))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <table width="100%" border="1">
        <tr>
            <th width="70">Unidade</th>
            <th width="40">Especialidade</th>
            <th width="30">Tipo Agendamento</th>
            <th width="15">Data</th>
            <th width="20">Dia da Semana</th>
            <th width="20">Encaixe</th>
            <th width="20">Ofertados</th>
            <th width="20">Agendados</th>
            <th width="20">Pacientes</th>
            <th width="20">Atendidos</th>
            <th width="20">Perda</th>
            <th width="20">Falta</th>
            <th width="20">% Perda</th>
            <th width="20">% Absenteísmo</th>
            <th width="20">%</th>
        </tr>
        <?php
        $ln = 2;
        ?>
        @foreach($data AS $row)
            <?php
                $encaixe  = $row->agendamentos_geral - $row->ofertas;
            ?>
            <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                <td class="left">{!! $row->arena !!}</td>
                <td class="left">{!! $row->linha_cuidado !!}</td>
                <td class="left">{!! $row->tipo_atendimento !!}</td>
                <td class="center">{!! \App\Http\Helpers\Util::DB2User($row->data) !!}</td>
                <td class="center">{!! $row->dias_semana !!}</td>
                <td class="center">{!! $encaixe > 0 ? $encaixe : 0 !!}</td>
                <td class="center">{!! $row->ofertas !!}</td>
                <td class="center">{!! $row->agendamentos_geral !!}</td>
                <td class="center">{!! $row->pacientes !!}</td>
                <td class="center">{!! $row->atendimentos !!}</td>
                <td class="center">=IFERROR(G{!! $ln !!}-H{!! $ln !!}, 0)</td>
                <td class="center">{!! $row->faltas !!}</td>
                <td class="center">=IF(K{!! $ln !!} > 0, IFERROR(K{!! $ln !!}/G{!! $ln !!}, 0), 0)</td>
                <td class="center">=IFERROR(L{!! $ln !!}/H{!! $ln !!}, 0)</td>
                <td class="center">=IFERROR(J{!! $ln !!}/G{!! $ln !!}, 0)</td>
            </tr>
            <?php
            $ln += 1;
            ?>
        @endforeach
        <tr class="line">
{{--            <td colspan="3"></td>--}}
{{--            <td class="center">=SUM(D2:D{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=SUM(E2:E{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=SUM(F2:F{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=SUM(G2:G{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=SUM(H2:H{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=SUM(I2:I{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=IFERROR(I{!! $ln  !!}/D{!! $ln !!}, 0)</td>--}}
{{--            <td class="center">=IFERROR(H{!! $ln  !!}/E{!! $ln !!}, 0)</td>--}}
{{--            <td class="center">=IFERROR(G{!! $ln  !!}/D{!! $ln !!}, 0)</td>--}}
{{--            <td class="center">=SUM(M2:M{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=SUM(N2:N{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=SUM(O2:O{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=SUM(P2:P{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=SUM(Q2:Q{!! $ln-1 !!})</td>--}}
{{--            <td class="center">=SUM(R2:R{!! $ln-1 !!})</td>--}}
        </tr>
    </table>

    </html>
@endif