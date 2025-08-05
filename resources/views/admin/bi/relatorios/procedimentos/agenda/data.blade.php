<?php

$lote = $params['contrato'];
$grupo = !empty($params['grupo']) ? $params['grupo'] : null;
$profissional = !empty($params['profissional']) ? $params['profissional'] : null;
$faturamento = \App\Faturamento::find($params['faturamento']);
$especialidade = $params['especialidade'];

$periodo = \App\Http\Helpers\Util::periodoMesPorAnoMes($faturamento->ano, $faturamento->mes);

$sub_grupos = \App\SubGrupos::getAll(!is_null($grupo) ? [$grupo] : null);
?>

@if(!empty($link))
    <div class="alert alert-success"><a href="{!! $link !!}" target="_blank" id="btn-click-download"><strong>Clique aqui</strong></a> para fazer download do arquivo em excel.</div>
@endif

<table width="100%" class="table table-striped table-responsive table-bordered  bg-light">
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

            $producao = \App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteProcedimento($lote, $faturamento->id, $procedimentos_id, false, [6, 10, 98, 99],
                $profissional);
            $_producao = [];
            if (!empty($producao)) {
                foreach ($producao AS $row) {
                    $_producao[$row->id] = (Object)$row->toArray();
                }
            }

            $faturado = \App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteProcedimento($lote, $faturamento->id, $procedimentos_id, true, [98, 99], $profissional);
            $_faturado = [];
            if (!empty($faturado)) {
                foreach ($faturado AS $row) {
                    $_faturado[$row->id] = (Object)$row->toArray();
                }
            }

            $gordura = \App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteProcedimento($lote, $faturamento->id, $procedimentos_id, false, [6, 10],
                $profissional);
            $_gordura = [];
            if (!empty($gordura)) {
                foreach ($gordura AS $row) {
                    $_gordura[$row->id] = (Object)$row->toArray();
                }
            }


            if (is_null($profissional)) {
                $absenteismo = \App\AtendimentoProcedimentos::getQuantidadeAbsenteismoByLoteProcediemnto($lote, $faturamento->id, $procedimentos_id);
                $_absenteismo = [];
                if (!empty($absenteismo)) {
                    foreach ($absenteismo AS $row) {
                        $_absenteismo[$row->id] = (Object)$row->toArray();
                    }
                }

                $agenda = \App\AtendimentoProcedimentos::getQuantidadeAbsenteismoByLoteProcediemnto($lote, $faturamento->id, $procedimentos_id,
                    [0, 1, 2, 3, 4, 5, 6, 7, 98, 99]);
                $_agenda = [];
                if (!empty($agenda)) {
                    foreach ($agenda AS $row) {
                        $_agenda[$row->id] = (Object)$row->toArray();
                    }
                }
            }

            ?>
            <tr>
                <th>{!! $grupo->grupo_codigo !!}.{!! $grupo->codigo !!}</th>
                <th align="align-left">{!! $grupo->descricao !!}</th>
                @if (is_null($profissional))
                    <th align="align-left">Agenda</th>
                @endif
                <th align="align-left">Produção</th>
                <th align="align-left">Gordura</th>
                @if (is_null($profissional))
                    <th align="align-left">Absenteísmo</th>
                @endif
            </tr>

            @foreach($procedimentos AS $procedimento)
                <?php
                $contrato = !empty($_contratos) && array_key_exists($procedimento->id, $_contratos) ? $_contratos[$procedimento->id] : null;
                $producao = !empty($_producao) && array_key_exists($procedimento->id, $_producao) ? $_producao[$procedimento->id] : null;
                $faturado = !empty($_faturado) && array_key_exists($procedimento->id, $_faturado) ? $_faturado[$procedimento->id] : null;
                $gordura = !empty($_gordura) && array_key_exists($procedimento->id, $_gordura) ? $_gordura[$procedimento->id] : null;
                $absenteismo = !empty($_absenteismo) && array_key_exists($procedimento->id, $_absenteismo) ? $_absenteismo[$procedimento->id] : null;
                $agenda = !empty($_agenda) && array_key_exists($procedimento->id, $_agenda) ? $_agenda[$procedimento->id] : null;

                $procedimento_sus = \App\Http\Helpers\Mask::ProcedimentoSUS($procedimento->sus);

                $graph[$procedimento_sus]['agenda'][] = !empty($agenda->total) ? $agenda->total : 0;
                $graph[$procedimento_sus]['producao'][] = !empty($producao->total) ? $producao->total : 0;
                $graph[$procedimento_sus]['gordura'][] = !empty($gordura->total) ? $gordura->total : 0;
                $graph[$procedimento_sus]['absenteismo'][] = !empty($absenteismo->total) ? $absenteismo->total : 0;
                ?>
                <tr class=" {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td nowrap>{!! $procedimento_sus !!}</td>
                    <td>{!! $procedimento->nome !!}</td>
                    @if (is_null($profissional))
                        <td width="">{!! !empty($agenda->total) ? $agenda->total : null !!}</td>
                    @endif
                    <td width="">{!! !empty($producao->total) ? $producao->total : null !!}</td>
                    <td width="">{!! !empty($gordura->total) ? $gordura->total : null !!}</td>
                    @if (is_null($profissional))
                        <td width="">{!! !empty($absenteismo->total) ? $absenteismo->total : null !!}</td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="100%">&nbsp;</td>
            </tr>
        @endif
    @endforeach
</table>
<?php
$_graph = null;
if (!empty($graph)) {
    foreach ($graph as $key => $row) {
        $_graph[$key]['agenda'] = array_sum($row['agenda']);
        $_graph[$key]['producao'] = array_sum($row['producao']);
        $_graph[$key]['gordura'] = array_sum($row['gordura']);
        $_graph[$key]['absenteismo'] = array_sum($row['absenteismo']);

        $__graph[] = "['" . $key . "','" . array_sum($row['agenda']) . "','" . array_sum($row['producao']) . "','" . array_sum($row['gordura']) . "','" . array_sum($row['absenteismo']) . "']";
        $__graph_keys[] = "'" . $key . "'";
    }
}

?>
<script>


</script>
