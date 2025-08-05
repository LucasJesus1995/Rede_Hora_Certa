<?php
$sub_grupos = \App\SubGrupos::getAll();

    $line = 2;
?>
<html>
<link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

<table width="100%" border="1">

    @foreach($sub_grupos AS $grupo)
        <?php
            $params['grupo'] = $grupo->grupo_codigo . $grupo->codigo;
            $procedimentos = \App\Procedimentos::getProcedimentosByGrupo($params);
        ?>
        @if(!empty($procedimentos[0]))
            <tr>
                <th>{!! $grupo->grupo_codigo !!}.{!! $grupo->codigo !!}</th>
                <th align="align-left">{!! $grupo->descricao !!}</th>
                <th colspan="3">Contrato</th>
                <th colspan="2">Demanda</th>
                <th colspan="1">% (D x C)</th>
                <th colspan="2">Produção</th>
                <th colspan="1">% (P x C)</th>
                <th colspan="1">% (P x D)</th>
                <th colspan="2">Faturado</th>
            </tr>

            <?php
                $line_block = $line;
            ?>
            @foreach($procedimentos AS $procedimento)
                <?php
                    $_contrato = \App\ContratoProcedimentos::getContratoProcedimentoByContratoProcedimento(array($procedimento->id));
                    $contrato = !empty($_contrato[0]) ? $_contrato[0] : null;

                    $producao = \App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteProcedimento($lote, $faturamento->id, $procedimento->id);
                    $faturado = \App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteProcedimento($lote, $faturamento->id, $procedimento->id, true);
                ?>
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td  width="20">{!! \App\Http\Helpers\Mask::ProcedimentoSUS($procedimento->sus) !!}</td>
                    <td  width="100">{!! $procedimento->nome !!}</td>

                    <td width="16" class="line-destaque">@if(!empty($contrato->valor_unitario)) {!! $contrato->valor_unitario !!} @endif</td>
                    <td width="10" class="line-destaque">@if(!empty($contrato->quantidade)) {!! $contrato->quantidade !!} @endif</td>
                    <td width="16" class="line-destaque">=(C{!! $line !!}*D{!! $line !!})</td>

                    <td width="10">@if(!empty($contrato->demanda)) {!! $contrato->demanda !!} @endif</td>
                    <td width="16">=(C{!! $line !!}*F{!! $line !!})</td>
                    <td width="10">=IF(D{!! $line !!}>0,(F{!! $line !!}/D{!! $line !!}),0)</td>

                    <td width="10" class="line-destaque">@if(!empty($producao->total)) {!! $producao->total !!} @endif</td>
                    <td width="16" class="line-destaque">=(C{!! $line !!}*I{!! $line !!})</td>
                    <td width="12" class="line-destaque">=IF(AND(I{!! $line !!}>0,D{!! $line !!}>0),(I{!! $line !!}/D{!! $line !!}),0)</td>
                    <td width="12" class="line-destaque">=IF(AND(I{!! $line !!}>0,F{!! $line !!}>0),(I{!! $line !!}/F{!! $line !!}),0)</td>

                    <td width="10">@if(!empty($faturado->total)) {!! $faturado->total !!} @endif</td>
                    <td width="16">=(C{!! $line !!}*M{!! $line !!})</td>

                </tr>
                <?php $line ++?>
            @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="bold">=SUM(D{!! $line_block !!}:D{!! ($line-1) !!})</td>
                    <td class="bold">=SUM(E{!! $line_block !!}:E{!! ($line-1) !!})</td>
                    <td class="bold">=SUM(F{!! $line_block !!}:F{!! ($line-1) !!})</td>
                    <td class="bold">=SUM(G{!! $line_block !!}:G{!! ($line-1) !!})</td>
                    <td></td>
                    <td class="bold">=SUM(I{!! $line_block !!}:I{!! ($line-1) !!})</td>
                    <td class="bold">=SUM(J{!! $line_block !!}:J{!! ($line-1) !!})</td>
                    <td></td>
                    <td></td>
                    <td class="bold">=SUM(M{!! $line_block !!}:M{!! ($line-1) !!})</td>
                    <td class="bold">=SUM(N{!! $line_block !!}:N{!! ($line-1) !!})</td>
                </tr>
                <?php $line ++?>
                <?php $line ++?>
                <?php $line ++?>
            <tr></tr>
        @endif
    @endforeach
</table>