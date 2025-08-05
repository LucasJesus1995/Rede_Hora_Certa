@if(!empty($relatorio))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <?php
    $ln = 3;
    ?>
    @if(!empty($relatorio))
        <table class="table table-striped table-responsive table-bordered  bg-light">
            @foreach($relatorio AS $row)
                <tr>
                    <th colspan="8">{!! $row['linha_cuidado']['nome'] !!}</th>
                </tr>

                @if(!empty($row['procedimentos']))
                    <tr>
                        <th width="40">Especialidade</th>
                        <th colspan="2">Procedimentos</th>
                        <th width="15">Valor</th>
                        <th width="15">Agendado</th>
                        <th width="15">Produção</th>
                        <th width="15">Faturamento</th>
                        <th width="18">Faturado ($)</th>
                    </tr>

                    <?php
                    $ln_linha_cuidado = $ln;

                    $_faturamento = \App\Procedimentos::getFaturamento($row['linha_cuidado']['id'], $faturamento->id);

                    $_producao = \App\Procedimentos::getProducaoPeriodo($row['linha_cuidado']['id'], $periodo['start'], $periodo['end']);
                    $_agendamento = \App\Procedimentos::getAgendadoPeriodo($row['linha_cuidado']['id'], $periodo['start'], $periodo['end']);

                    ?>
                    @foreach($row['procedimentos'] AS $procedimento)
                        <?php
                        $_contrato = \App\Procedimentos::getValorProcedimentoContrato($contrato, $procedimento['id']);
                        ?>
                        <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                            <td class="align-left">{!! $row['linha_cuidado']['nome'] !!}</td>
                            <td width="15">{!! \App\Http\Helpers\Mask::CodigoProcedimento($procedimento['sus']) !!}</td>
                            <td width="100" class="align-left">{!! $procedimento['nome'] !!}</td>
                            <td class="align-left">@if(!empty($_contrato->id)) {!! $_contrato->valor_unitario !!} @endif</td>
                            <td class="align-left">@if(array_key_exists($procedimento['id'], $_agendamento)){!! $_agendamento[$procedimento['id']] !!}@endif</td>
                            <td class="align-left">@if(array_key_exists($procedimento['id'], $_producao)){!! $_producao[$procedimento['id']] !!}@endif</td>
                            <td class="align-left">@if(array_key_exists($procedimento['id'], $_faturamento)){!! $_faturamento[$procedimento['id']] !!}@endif</td>
                            <td class="align-left">=(D{!! $ln !!}*G{!! $ln !!})</td>
                        </tr>
                        <?php $ln++; ?>
                    @endforeach
                    <tr class="">
                        <th class="align-left" colspan="4"></th>
                        <th class="align-left">=SUM(E{!! $ln_linha_cuidado !!}:E{!! ($ln-1) !!})</th>
                        <th class="align-left">=SUM(F{!! $ln_linha_cuidado !!}:F{!! ($ln-1) !!})</th>
                        <th class="align-left">=SUM(G{!! $ln_linha_cuidado !!}:G{!! ($ln-1) !!})</th>
                        <th class="align-left">=SUM(H{!! $ln_linha_cuidado !!}:H{!! ($ln-1) !!})</th>
                    </tr>
                    <?php $ln++; ?>
                @endif
                <tr><td></td></tr>
                <?php $ln++; ?>
                <?php $ln++; ?>
                <?php $ln++; ?>
            @endforeach
        </table>
    @endif
    </html>
@endif