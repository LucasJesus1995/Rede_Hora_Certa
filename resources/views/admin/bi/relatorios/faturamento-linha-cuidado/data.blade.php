<html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <table width="100%" border="1">

        <tr>
            <th></th>
            <th colspan="2">Procedimentos</th>
            <th colspan="3">Contrato</th>
            <th colspan="3">Oferta</th>
            <th colspan="2">Produção (Meses Anterior)</th>
            <th colspan="2">Produção ({!! \App\Http\Helpers\Util::getMesNome($faturamento->mes) !!})</th>
            <th colspan="4">Faturamento ({!! \App\Http\Helpers\Util::getMesNome($faturamento->mes) !!})</th>
        </tr>

        <tr>
            <th width="35">Especialidade(s)</th>

            <th width="95">Nome</th>
            <th width="20">Códigos</th>

            <th width="14">Qtde</th>
            <th width="20">Valor</th>
            <th width="20">Total</th>

            <th width="14">Qtde</th>
            <th width="14">% Contrato</th>
            <th width="20">Valor</th>

            <th width="14">Qtde</th>

            <th width="20">Valor</th>

            <th width="14">Qtde</th>
            <th width="20">Total</th>

            <th width="14">Qtde</th>
            <th width="14">% Contrato</th>
            <th width="14">% Oferta</th>
            <th width="20">Total</th>
        </tr>
        <?php
            $ln = 3;

            $date = \App\Http\Helpers\Util::periodoMesPorAnoMes($faturamento->ano, $faturamento->mes);

            $contrato_procedimentos = \App\Procedimentos::getContratoProcedimentoListByLote($lote);
        ?>
        @foreach($linhas_cuidado AS $cod_linha_cuidado => $linha_cuidado)
            <?php
                $ln_start = $ln;
                $procedimentos = App\Procedimentos::getProcedimentosFaturadosPorLinhaCuidadoAgenda($faturamento->id, $cod_linha_cuidado, $lote);
                $procedimento_producao_mes_anterior = \App\Procedimentos::getProducaoMesLinhaCuidadoAgenda($cod_linha_cuidado, [6], $date['start'], '<');
                $procedimento_producao_mes = \App\Procedimentos::getProducaoMesLinhaCuidadoAgenda($cod_linha_cuidado,[6,98,99], null, null, $date);
                $procedimento_ofertas = \App\OfertaLoteLinhaCuidado::getByLoteAnoMes($lote, $faturamento->ano, $faturamento->mes, null, $cod_linha_cuidado);

                $sum_oferta = !empty($procedimento_ofertas) ? array_sum(array_column($procedimento_ofertas->toArray(), 'qtd')) : 0;

                $_contrato_procedimentos = \App\Procedimentos::getProcedimentoContratoByLoteLinhaCuidado($lote, $cod_linha_cuidado);
                $total_contrato_by_procedimento = array_sum(array_column($_contrato_procedimentos->toArray(), 'quantidade_contrato'));

                foreach ($procedimentos AS $k => $procedimento){
                    if(
                        array_key_exists($procedimento->cod_procedimento, $contrato_procedimentos)
                        && in_array($procedimento->cod_procedimento, array_column($_contrato_procedimentos->toArray(), 'cod_procedimento'))
                    ){
                        $percentual = \App\Http\Helpers\Util::getPorcentagem( $contrato_procedimentos[$procedimento->cod_procedimento]["quantidade"], $total_contrato_by_procedimento);
                        $rateio = (int) \App\Http\Helpers\Util::getPorcentagemEquivalente($percentual, $sum_oferta);
                        $procedimentos[$k]->rateio = $rateio;
                    } else {
                        $procedimentos[$k]->rateio = 0;
                    }
                }

                $produzido_sum = array();
            ?>
            @foreach($procedimentos AS $procedimento)
                <?php
                    $produzido_sum[] = $procedimento->faturado;

                    $producao_mes_anterior = array_key_exists($procedimento->cod_procedimento, $procedimento_producao_mes_anterior) ? $procedimento_producao_mes_anterior[$procedimento->cod_procedimento] : 0;
                    $producao_mes = array_key_exists($procedimento->cod_procedimento, $procedimento_producao_mes) ? $procedimento_producao_mes[$procedimento->cod_procedimento] : 0;

                    $contrato_valor = array_key_exists($procedimento->cod_procedimento, $contrato_procedimentos) ? $contrato_procedimentos[$procedimento->cod_procedimento]['valor_unitario'] : null;
                    $contrato_quantidade = array_key_exists($procedimento->cod_procedimento, $contrato_procedimentos) ? $contrato_procedimentos[$procedimento->cod_procedimento]['quantidade'] : null;

                ?>
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td>{!! $linha_cuidado !!}</td>

                    <td>{!! $procedimento->procedimento !!}</td>
                    <td>{!! \App\Http\Helpers\Mask::CodigoProcedimento($procedimento->codigo) !!}</td>

                    <td>{!! $contrato_quantidade !!}</td>
                    <td>{!! $contrato_valor !!}</td>
                    <td>=IF(E{!! $ln !!}>0,E{!! $ln !!}*D{!! $ln !!},0)</td>

                    <td>{!! $procedimento->rateio !!}</td>
                    <td>=IF(G{!! $ln !!}>0,((G{!! $ln !!}/D{!! $ln !!})-1),0)</td>
                    <td>=IF(G{!! $ln !!}>0,E{!! $ln !!}*G{!! $ln !!},0)</td>

                    <td>{!! $producao_mes_anterior !!}</td>
                    <td>=IF(E{!! $ln !!}>0,E{!! $ln !!}*J{!! $ln !!},0)</td>

                    <td>{!! $producao_mes !!}</td>
                    <td>=IF(E{!! $ln !!}>0,E{!! $ln !!}*L{!! $ln !!},0)</td>

                    <td>{!! $procedimento->faturado !!}</td>
                    <td>=IF(N{!! $ln !!}>0,((N{!! $ln !!}/D{!! $ln !!})-1),0)</td>
                    <td>=IF(G{!! $ln !!}>0,((N{!! $ln !!}/G{!! $ln !!})-1),0)</td>
                    <td>=IF(E{!! $ln !!}>0,E{!! $ln !!}*N{!! $ln !!},0)</td>
                </tr>
                <?php $ln++; ?>
            @endforeach
            @if(array_sum($produzido_sum) > 0)
                <tbody>
                    <tr>
                        <th colspan="3"></th>
                        <th>=SUM(D{!! $ln_start !!}:D{!! ($ln-1) !!})</th>
                        <th>=SUM(E{!! $ln_start !!}:E{!! ($ln-1) !!})</th>
                        <th>=SUM(F{!! $ln_start !!}:F{!! ($ln-1) !!})</th>
                        <th>=SUM(G{!! $ln_start !!}:G{!! ($ln-1) !!})</th>
                        <th></th>
                        <th>=SUM(I{!! $ln_start !!}:I{!! ($ln-1) !!})</th>
                        <th>=SUM(J{!! $ln_start !!}:J{!! ($ln-1) !!})</th>
                        <th>=SUM(K{!! $ln_start !!}:K{!! ($ln-1) !!})</th>
                        <th>=SUM(L{!! $ln_start !!}:L{!! ($ln-1) !!})</th>
                        <th>=SUM(M{!! $ln_start !!}:M{!! ($ln-1) !!})</th>
                        <th>=SUM(N{!! $ln_start !!}:N{!! ($ln-1) !!})</th>
                        <th></th>
                        <th></th>
                        <th>=SUM(Q{!! $ln_start !!}:Q{!! ($ln-1) !!})</th>
                    </tr>
                </tbody>
                <tr></tr>
                <?php $ln++; ?>
                <?php $ln++; ?>
            @endif

        @endforeach

    </table>
</html>