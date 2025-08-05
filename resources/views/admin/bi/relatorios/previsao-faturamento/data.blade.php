<html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <table width="100%" border="1">
        <tr>

            <th colspan="3"></th>
            <th colspan="2">Contrato</th>
            <th colspan="3">Oferta (Rateio)</th>
            <th colspan="3">Produção</th>
            <th colspan="5">Faturado</th>
            <th colspan="2">Saldo do Meses (Anterior)</th>
            <th colspan="2">Saldo (Uso)</th>

        </tr>
        <tr>
            <th width="30">Especialidade</th>
            <th colspan="2">Códigos</th>
            <th width="15">Qtde</th>
            <th width="15">Valor</th>

            <th width="15">Qtde</th>
            <th width="10">%</th>
            <th width="15">Valor</th>

            <th width="15">Qtde</th>
            <th width="10">%</th>
            <th width="15">Valor</th>

            <th width="15">Qtde</th>
            <th width="10">ATD</th>
            <th width="10">OFT</th>
            <th width="10">CTT</th>
            <th width="15">Valor</th>

            <th width="15">Qtde</th>
            <th width="15">Valor</th>
            <th width="15">Qtde</th>
            <th width="15">Valor</th>

        </tr>
        <?php
            $total_geral_contrato[] = 0;
            $total_geral_ofertas[] = 0;
            $total_geral_producao[] = 0;
            $total_geral_faturado[] = 0;

            $contrato_quantidade_geral[] = 0;
            $rateio_quantidade_geral[] = 0;
            $produzido_quantidade_geral[] = 0;
            $faturado_quantidade_geral[] = 0;
        ?>
        @foreach($linhas_cuidado AS $cod_linha_cuidado => $linha_cuidado)
            <?php
                $procedimentos = App\Procedimentos::getProcedimentoContratoByLoteLinhaCuidado($lote, $cod_linha_cuidado);

                $producao = App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteLinhaCuidadoData($lote, $cod_linha_cuidado, $faturamento->id, false);
                $faturado = App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteLinhaCuidadoData($lote, $cod_linha_cuidado, $faturamento->id, true);

                $gordura = \App\Http\Helpers\Relatorios\GorduraHelpers::getGorduraByLinhaCuidado($cod_linha_cuidado, $faturamento->ano, $faturamento->mes);

                $total_contrato = null;
                $total_sum_faturado = null;
                $total_sum_rateio = null;
                $total_sum_produzido = null;

                $contrato_quantidade = null;
                $rateio_quantidade = null;
                $produzido_quantidade = null;
                $faturado_quantidade = null;

                $gordura_geral_total = null;
                $gordura_geral_sum = null;
                $gordura_total_uso_total = null;
                $gordura_total_uso_sum_total = null;

                $ofertas = \App\OfertaLoteLinhaCuidado::getByLoteAnoMes($lote, $faturamento->ano, $faturamento->mes, null, $cod_linha_cuidado);

                $sum_oferta = !empty($ofertas) ? array_sum(array_column($ofertas->toArray(), 'qtd')) : 0;

                $total_contrato_by_procedimento = array_sum(array_column($procedimentos->toArray(), 'quantidade_contrato'));
                foreach ($procedimentos AS $k => $procedimento){
                    $percentual = \App\Http\Helpers\Util::getPorcentagem($procedimento->quantidade_contrato, $total_contrato_by_procedimento);
                    $rateio = (int) \App\Http\Helpers\Util::getPorcentagemEquivalente($percentual, $sum_oferta);
                    $procedimentos[$k]->rateio = $rateio;
                }
            ?>
            @if(count($procedimentos))
                @foreach($procedimentos AS $procedimento)
                    <?php
                        $valor_contrato = $procedimento->valor_contrato;
                        $quantidade_contrato = $procedimento->quantidade_contrato;

                        $contrato_quantidade[] = $quantidade_contrato;
                        $contrato_quantidade_geral[] = $quantidade_contrato;
                        $total_contrato[] = ($valor_contrato * $quantidade_contrato);
                        $total_geral_contrato[] = ($valor_contrato * $quantidade_contrato);
                        $total_produzido = 0;
                        if(array_key_exists($procedimento->cod_procedimento, $producao)){
                            $total_produzido = $producao[$procedimento->cod_procedimento]['total'];
                        }

                        $total_faturado = 0;
                        if(array_key_exists($procedimento->cod_procedimento, $faturado)){
                            $total_faturado = $faturado[$procedimento->cod_procedimento]['total'];
                        }

                        $sum_faturado = $total_faturado * $valor_contrato;
                        $faturado_quantidade[] = $total_faturado;
                        $faturado_quantidade_geral[] = $total_faturado;
                        $total_sum_faturado[] = $sum_faturado;
                        $total_geral_faturado[] = $sum_faturado;

                        $sum_produzido = $total_produzido * $valor_contrato;
                        $produzido_quantidade[] = $total_produzido;
                        $produzido_quantidade_geral[] = $total_produzido;
                        $total_sum_produzido[] = $sum_produzido;
                        $total_geral_producao[] = $sum_produzido;

                        $sum_rateio = $procedimento->rateio * $valor_contrato;
                        $total_sum_rateio[] = $sum_rateio;
                        $rateio_quantidade[] = $procedimento->rateio;
                        $rateio_quantidade_geral[] = $procedimento->rateio;
                        $total_geral_ofertas[] = $sum_rateio;

                        $gordura_total = array_key_exists($procedimento->cod_procedimento, $gordura) ? $gordura[$procedimento->cod_procedimento]['total'] : null;
                        $gordura_total_sum = !is_null($gordura_total) ? ($gordura_total * $valor_contrato) : null;

                        $gordura_geral_total[] = $gordura_total;
                        $gordura_geral_sum[] = $gordura_total_sum;

                        $gordura_total_uso = ($total_produzido < $total_faturado) ? ($total_faturado - $total_produzido) : null;
                        $gordura_total_uso_sum = !is_null($gordura_total_uso) ? ($gordura_total_uso * $valor_contrato) : null;

                        $gordura_total_uso_total[] = $gordura_total_uso;
                        $gordura_total_uso_sum_total[] = $gordura_total_uso_sum;
                    ?>
                    <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                        <td>{!! $linha_cuidado !!}</td>
                        <td width="15">{!! \App\Http\Helpers\Mask::CodigoProcedimento($procedimento->codigo) !!}</td>
                        <td width="75">{!! $procedimento->procedimento !!}</td>

                        <td>{!! $quantidade_contrato  !!}</td>
                        <td class="right">{!! number_format($valor_contrato, 2, ",", ".")  !!}</td>

                        <td class="green-10">{!! $procedimento->rateio !!}</td>
                        <td class="green-50">{!! ($procedimento->rateio / $quantidade_contrato) !!}</td>
                        <td class="right green-80">{!! number_format($sum_rateio, 2, ",", ".") !!}</td>

                        <td class="green-10">{!! $total_produzido !!}</td>
                        <td class="green-50">{!! ($total_produzido /$quantidade_contrato) !!}</td>
                        <td class="right green-80">{!! number_format($sum_produzido, 2, ",", ".") !!}</td>

                        <td class="green-10">{!! $total_faturado !!}</td>
                        <td class="green-50">{!! ($total_produzido > 0 && $total_faturado > 0) ? ($total_faturado / $total_produzido) : null !!}</td>
                        <td class="green-50">{!! ($procedimento->rateio > 0 && $total_faturado > 0) ? ($total_faturado / $procedimento->rateio) : null !!}</td>
                        <td class="green-50">{!! ($total_faturado / $quantidade_contrato) !!}</td>
                        <td class="right green-80">{!! number_format($sum_faturado, 2, ",", ".") !!}</td>

                        <td class="green-10">{!! $gordura_total !!}</td>
                        <td class="right green-10">{!! !is_null($gordura_total_sum) ? number_format($gordura_total_sum, 2, ",", ".") : null; !!}</td>
                        <td class="green-10">{!! $gordura_total_uso !!}</td>
                        <td class="green-10">{!! !is_null($gordura_total_uso_sum) ? number_format($gordura_total_uso_sum, 2, ",", ".") : null; !!}</td>
                    </tr>
                @endforeach
                <tbody>
                    <tr>
                        <td colspan="3" class="right"></td>
                        <td colspan="1" class="right">{!! is_array($contrato_quantidade) ? array_sum($contrato_quantidade) : 0 !!}</td>
                        <td colspan="1" class="right">{!! is_array($total_contrato) ? number_format(array_sum($total_contrato), 2, ",", ".") : 0 !!}</td>
                        <td colspan="1" class="right">{!! is_array($rateio_quantidade) ? array_sum($rateio_quantidade) : 0 !!}</td>
                        <td colspan="2" class="right">{!! is_array($total_sum_rateio) ? number_format(array_sum($total_sum_rateio), 2, ",", ".") : 0 !!}</td>
                        <td colspan="1" class="right">{!! is_array($produzido_quantidade) ? array_sum($produzido_quantidade) : 0 !!}</td>
                        <td colspan="2" class="right">{!! is_array($total_sum_produzido) ? number_format(array_sum($total_sum_produzido), 2, ",", ".") : 0 !!}</td>
                        <td colspan="1" class="right">{!! is_array($faturado_quantidade) ? array_sum($faturado_quantidade) : 0 !!}</td>
                        <td colspan="4" class="right">{!! is_array($total_sum_faturado) ? number_format(array_sum($total_sum_faturado), 2, ",", ".") : 0 !!}</td>

                        <td colspan="1" class="right">{!! is_array($gordura_geral_total) ? array_sum($gordura_geral_total) : 0 !!}</td>
                        <td colspan="1" class="right">{!! is_array($gordura_geral_sum) ? number_format(array_sum($gordura_geral_sum), 2, ",", ".") : 0 !!}</td>
                        <td colspan="1" class="right">{!! is_array($gordura_total_uso_total) ? array_sum($gordura_total_uso_total) : 0 !!}</td>
                        <td colspan="1" class="right">{!! is_array($gordura_total_uso_sum_total) ? number_format(array_sum($gordura_total_uso_sum_total), 2, ",", ".") : 0 !!}</td>
                    </tr>
                    <tr></tr>
                </tbody>
            @endif
        @endforeach
        @if(count($linhas_cuidado) > 1)
            <tbody>
                <tr>
                    <th colspan="3"></th>
                    <th colspan="1">{!! array_sum($contrato_quantidade_geral) !!}</th>
                    <th colspan="1">{!! number_format(array_sum($total_geral_contrato), 2, ",", ".") !!}</th>
                    <th colspan="1">{!! array_sum($rateio_quantidade_geral) !!}</th>
                    <th colspan="2">{!! number_format(array_sum($total_geral_ofertas), 2, ",", ".") !!}</th>
                    <th colspan="1">{!! array_sum($produzido_quantidade_geral) !!}</th>
                    <th colspan="2">{!! number_format(array_sum($total_geral_producao), 2, ",", ".") !!}</th>
                    <th colspan="1">{!! array_sum($faturado_quantidade_geral) !!}</th>
                    <th colspan="4">{!! number_format(array_sum($total_geral_faturado), 2, ",", ".") !!}</th>
                </tr>
            </tbody>
        @endif
    </table>
</html>