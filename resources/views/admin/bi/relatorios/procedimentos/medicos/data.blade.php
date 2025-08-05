<?php

$contrato = $params['contrato'];
$faturamento = \App\Faturamento::find($params['faturamento']);
$especialidade = $params['especialidade'];

$periodo = \App\Http\Helpers\Util::periodoMesPorAnoMes($faturamento->ano, $faturamento->mes);


$linhas_cuidado = !empty($especialidade) ? \App\LinhaCuidado::where('id', $especialidade)->get() : \App\LinhaCuidado::all();

foreach ($linhas_cuidado AS $linha_cuidado) {
    $_data = \App\Procedimentos::AbsenteismoCancelamento($periodo, $linha_cuidado->id);

    if (!empty($_data)) {
        $procedimento_perdas[$linha_cuidado->id] = $_data;
    }
}

$procedimentos = [];
if (!empty($procedimento_perdas)) {
    foreach ($procedimento_perdas As $perdas) {
        foreach ($perdas AS $k => $perda) {
            if (array_key_exists($k, $procedimentos)) {
                $procedimentos[$k]['quantidade'] += $perda['quantidade'];
            } else {
                $procedimentos[$k] = $perda;
            }
        }
    }
}
?>

@if(!empty($procedimentos))
    <div class="">
        <table width="100%" class="table table-striped table-responsive table-bordered  bg-light">
            <tr>
                <th colspan="2">Procedimento</th>
                <th rowspan="2">Qtd</th>
                <th rowspan="2">Contrato</th>
            </tr>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Total</th>
            </tr>
            @foreach($procedimentos AS $procedimento)
                <?php
                $contrato_procedimento = \App\Procedimentos::getValorProcedimentoContrato($contrato,
                    $procedimento['id']);

                $quantidade = $procedimento['quantidade'];
                $contrato_valor_unitario = !empty($contrato_procedimento) ? $contrato_procedimento->valor_unitario : 0;

                $total_procedimento = $quantidade * $contrato_valor_unitario;
                $_total_procedimento[] = $total_procedimento;
                ?>
                <tr>
                    <td nowrap>{!! \App\Http\Helpers\Mask::CodigoProcedimento($procedimento['sus']) !!}</td>
                    <td>{!! $procedimento['nome'] !!}</td>
                    <td>{!! $quantidade !!}</td>
                    <td class="align-right">{!! number_format($contrato_valor_unitario, 2, ',', '.') !!}</td>
                    <td class="align-right">{!! number_format($total_procedimento, 2, ',', '.') !!}</td>

                </tr>
            @endforeach
            <tr>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td>{!! number_format(array_sum($_total_procedimento), 2, ',', '.') !!}</td>
            </tr>
        </table>
    </div>
@else
    <div class="alert alert-danger">Nenhum resultado encontrado!</div>
@endif