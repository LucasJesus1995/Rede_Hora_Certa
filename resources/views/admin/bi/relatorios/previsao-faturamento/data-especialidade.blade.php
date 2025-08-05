<html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <table width="100%" border="1">
        <tr>
            <th width="50">Especialidade</th>
            <th width="15">Contrato</th>
            <th width="15">Oferta</th>
            <th width="15">Produção</th>
            <th width="15">Faturado</th>
        </tr>
        @foreach($linhas_cuidado AS $cod_linha_cuidado => $linha_cuidado)
            <?php
                $procedimentos = App\Procedimentos::getProcedimentoContratoByLoteLinhaCuidado($lote, $cod_linha_cuidado);

                $producao = App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteLinhaCuidadoData($lote, $cod_linha_cuidado, $faturamento->id, false);
                $faturado = App\AtendimentoProcedimentos::getQuantidadeProduzidaMesByLoteLinhaCuidadoData($lote, $cod_linha_cuidado, $faturamento->id, true);

                $ofertas = \App\OfertaLoteLinhaCuidado::getByLoteAnoMes($lote, $faturamento->ano, $faturamento->mes, null, $cod_linha_cuidado);
                $quantidade_oferta = !empty($ofertas) ? array_sum(array_column($ofertas->toArray(), 'qtd')) : 0;

                $quantidade_contrato = array_sum(array_column($procedimentos->toArray(), 'quantidade_contrato'));

                $quantidade_producao = 0;
                if(!empty($producao)){
                    foreach ($producao as $row) {
                        $quantidade_producao += $row['total'];
                    }
                }

                $quantidade_faturado = 0;
                if(!empty($faturado)){
                    foreach ($faturado as $row) {
                        $quantidade_faturado += $row['total'];
                    }
                }
            ?>
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td>{!! $linha_cuidado !!}</td>
                    <td>{!! $quantidade_contrato !!}</td>
                    <td>{!! $quantidade_oferta !!}</td>
                    <td>{!! $quantidade_producao !!}</td>
                    <td>{!! $quantidade_faturado !!}</td>
                </tr>
        @endforeach
    </table>
</html>