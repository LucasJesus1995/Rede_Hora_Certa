<?php

$lote = $params['contrato'];
$grupo = !empty($params['grupo']) ? $params['grupo'] : null;
$faturamento = \App\Faturamento::find($params['faturamento']);
$especialidade = $params['especialidade'];

$periodo = \App\Http\Helpers\Util::periodoMesPorAnoMes($faturamento->ano, $faturamento->mes);

$sub_grupos = \App\SubGrupos::getAll(!is_null($grupo) ? [$grupo] : null);

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
            <?php
            $procedimentos_id = array_column($procedimentos->toArray(), 'id');
            $contratos = \App\ContratoProcedimentos::getContratoProcedimentoByContratoProcedimento($procedimentos_id);
            $_contratos = [];
            if (!empty($contratos)) {
                foreach ($contratos AS $contrato) {
                    $_contratos[$contrato->procedimento] = (Object)$contrato->toArray();
                }
            }

            $producao = \App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteProcedimento($lote, $faturamento->id, $procedimentos_id, false);
            $_producao = [];
            if (!empty($producao)) {
                foreach ($producao AS $row) {
                    $_producao[$row->id] = (Object)$row->toArray();
                }
            }

            $faturado = \App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteProcedimento($lote, $faturamento->id, $procedimentos_id, true, [98, 99]);
            $_faturado = [];
            if (!empty($faturado)) {
                foreach ($faturado AS $row) {
                    $_faturado[$row->id] = (Object)$row->toArray();
                }
            }

            $gordura = \App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteProcedimento($lote, $faturamento->id, $procedimentos_id, false, [6, 10]);
            $_gordura = [];
            if (!empty($gordura)) {
                foreach ($gordura AS $row) {
                    $_gordura[$row->id] = (Object)$row->toArray();
                }
            }

            $absenteismo = \App\AtendimentoProcedimentos::getQuantidadeAbsenteismoByLoteProcediemnto($lote, $faturamento->id, $procedimentos_id);
            $_absenteismo = [];
            if (!empty($absenteismo)) {
                foreach ($absenteismo AS $row) {
                    $_absenteismo[$row->id] = (Object)$row->toArray();
                }
            }

            $agenda = \App\AtendimentoProcedimentos::getQuantidadeAbsenteismoByLoteProcediemnto($lote, $faturamento->id, $procedimentos_id, [0, 1, 2, 3, 4, 5, 6, 7, 98, 99]);
            $_agenda = [];
            if (!empty($agenda)) {
                foreach ($agenda AS $row) {
                    $_agenda[$row->id] = (Object)$row->toArray();
                }
            }

            ?>
            <tr>
                <th width="20">{!! $grupo->grupo_codigo !!}.{!! $grupo->codigo !!}</th>
                <th width="100">{!! $grupo->descricao !!}</th>
                <th width="16">Contrato (C)</th>
                <th width="16">C. Valor</th>
                <th width="16">C. Total</th>

                <th width="16">Demanda (D)</th>
                <th width="10">D % C</th>
                <th width="16">D. Total</th>

                <th width="16">Agenda (A)</th>
                <th width="10">A % C</th>
                <th width="10">A % D</th>
                <th width="16">A. Total</th>

                <th width="16">Produção (P)</th>
                <th width="10">P % C</th>
                <th width="10">P % D</th>
                <th width="10">P % A</th>
                <th width="16">P. Total</th>

                <th width="16">Faturado (F)</th>
                <th width="10">F % P</th>
                <th width="16">F. Total</th>

                <th width="16">Gordura (G)</th>
                <th width="10">G % P</th>
                <th width="16">G. Total</th>

                <th width="16">Absenteísmo (AB)</th>
                <th width="10">AB % P</th>
                <th width="10">AB % A</th>
                <th width="16">AB. Total</th>
            </tr>
            <?php
            $line_block = $line;
            ?>
            @foreach($procedimentos AS $procedimento)
                <?php
                $contrato = !empty($_contratos) && array_key_exists($procedimento->id, $_contratos) ? $_contratos[$procedimento->id] : null;
                $producao = !empty($_producao) && array_key_exists($procedimento->id, $_producao) ? $_producao[$procedimento->id] : null;
                $faturado = !empty($_faturado) && array_key_exists($procedimento->id, $_faturado) ? $_faturado[$procedimento->id] : null;
                $gordura = !empty($_gordura) && array_key_exists($procedimento->id, $_gordura) ? $_gordura[$procedimento->id] : null;
                $absenteismo = !empty($_absenteismo) && array_key_exists($procedimento->id, $_absenteismo) ? $_absenteismo[$procedimento->id] : null;
                $agenda = !empty($_agenda) && array_key_exists($procedimento->id, $_agenda) ? $_agenda[$procedimento->id] : null;
                ?>
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td>{!! \App\Http\Helpers\Mask::ProcedimentoSUS($procedimento->sus) !!}</td>
                    <td>{!! $procedimento->nome !!}</td>

                    <td class="line-destaque">{!! !empty($contrato->quantidade) ? $contrato->quantidade : null !!}</td>
                    <td class="line-destaque">{!! !empty($contrato->valor_unitario) ? $contrato->valor_unitario : null !!}</td>
                    <td class="line-destaque">=(C{!! $line !!}*D{!! $line !!})</td>

                    <td>{!! !empty($contrato->demanda) ? $contrato->demanda : null !!}</td>
                    <td>=IF(AND(C{!! $line !!}>0,F{!! $line !!}>0),(F{!! $line !!}/C{!! $line !!}),0)</td>
                    <td>=(D{!! $line !!}*F{!! $line !!})</td>

                    <td class="line-destaque">{!! !empty($agenda->total) ? $agenda->total : null !!}</td>
                    <td class="line-destaque">=IF(AND(C{!! $line !!}>0,I{!! $line !!}>0),(I{!! $line !!}/C{!! $line !!}),0)</td>
                    <td class="line-destaque">=IF(AND(F{!! $line !!}>0,I{!! $line !!}>0),(I{!! $line !!}/F{!! $line !!}),0)</td>
                    <td class="line-destaque">=(D{!! $line !!}*I{!! $line !!})</td>

                    <td>{!! !empty($producao->total) ? $producao->total : null !!}</td>
                    <td>=IF(AND(C{!! $line !!}>0,M{!! $line !!}>0),(M{!! $line !!}/C{!! $line !!}),0)</td>
                    <td>=IF(AND(F{!! $line !!}>0,M{!! $line !!}>0),(M{!! $line !!}/F{!! $line !!}),0)</td>
                    <td>=IF(AND(I{!! $line !!}>0,M{!! $line !!}>0),(M{!! $line !!}/I{!! $line !!}),0)</td>
                    <td>=(D{!! $line !!}*M{!! $line !!})</td>

                    <td class="line-destaque">{!! !empty($faturado->total) ? $faturado->total : null !!}</td>
                    <td class="line-destaque">=IF(AND(C{!! $line !!}>0,R{!! $line !!}>0),(R{!! $line !!}/C{!! $line !!}),0)</td>
                    <td class="line-destaque">=(D{!! $line !!}*R{!! $line !!})</td>

                    <td>{!! !empty($gordura->total) ? $gordura->total : null !!}</td>
                    <td>=IF(AND(M{!! $line !!}>0,U{!! $line !!}>0),(U{!! $line !!}/M{!! $line !!}),0)</td>
                    <td>=(D{!! $line !!}*U{!! $line !!})</td>

                    <td class="line-destaque">{!! !empty($absenteismo->total) ? $absenteismo->total : null !!}</td>
                    <td class="line-destaque">=IF(AND(M{!! $line !!}>0,X{!! $line !!}>0),(X{!! $line !!}/M{!! $line !!}),0)</td>
                    <td class="line-destaque">=IF(AND(I{!! $line !!}>0,X{!! $line !!}>0),(X{!! $line !!}/I{!! $line !!}),0)</td>
                    <td class="line-destaque">=(D{!! $line !!}*X{!! $line !!})</td>
                </tr>
                <?php $line++?>
            @endforeach
            <tr>
                <td></td>
                <td></td>

                <td class="bold">=SUM(C{!! $line_block !!}:C{!! ($line-1) !!})</td>
                <td class="bold"></td>
                <td class="bold">=SUM(E{!! $line_block !!}:E{!! ($line-1) !!})</td>

                <td class="bold">=SUM(F{!! $line_block !!}:F{!! ($line-1) !!})</td>
                <td class="bold"></td>
                <td class="bold">=SUM(H{!! $line_block !!}:H{!! ($line-1) !!})</td>

                <td class="bold">=SUM(I{!! $line_block !!}:I{!! ($line-1) !!})</td>
                <td class="bold"></td>
                <td class="bold"></td>
                <td class="bold">=SUM(L{!! $line_block !!}:L{!! ($line-1) !!})</td>

                <td class="bold">=SUM(M{!! $line_block !!}:M{!! ($line-1) !!})</td>
                <td class="bold"></td>
                <td class="bold"></td>
                <td class="bold"></td>
                <td class="bold">=SUM(Q{!! $line_block !!}:Q{!! ($line-1) !!})</td>

                <td class="bold">=SUM(R{!! $line_block !!}:R{!! ($line-1) !!})</td>
                <td class="bold"></td>
                <td class="bold">=SUM(T{!! $line_block !!}:T{!! ($line-1) !!})</td>

                <td class="bold">=SUM(U{!! $line_block !!}:U{!! ($line-1) !!})</td>
                <td class="bold"></td>
                <td class="bold">=SUM(W{!! $line_block !!}:W{!! ($line-1) !!})</td>

                <td class="bold">=SUM(X{!! $line_block !!}:X{!! ($line-1) !!})</td>
                <td class="bold"></td>
                <td class="bold"></td>
                <td class="bold">=SUM(AA{!! $line_block !!}:AA{!! ($line-1) !!})</td>
            </tr>
            <?php $line++?>
            <?php $line++?>
            <?php $line++?>
            <tr></tr>
        @endif
    @endforeach
</table>