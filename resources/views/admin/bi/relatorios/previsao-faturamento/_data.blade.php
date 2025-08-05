@if(!empty($rows))
    @foreach($rows['data'] AS $key => $data)
        <div class="well well-small">
            <div id="container-{!! $key !!}" style="min-height: {!!  100+(60*count($data)) !!}px"></div>
            <hr />
            <table class="table table-striped table-responsive table-bordered bg-light" border="1" width="100%" >
                <thead>
                <tr>
                    <th rowspan="2" class="align-center" style="vertical-align: middle">Procedimento</th>
                    <th colspan="2" class="align-center">Contrato</th>
                    <th colspan="4" class="align-center">Produção CIES</th>
                    <th colspan="2" class="align-center">Demostrativo</th>
                </tr>
                    <tr>
                        <th>Valor</th>
                        <th>Contrato</th>
                        <th>Produzido</th>
                        <th>Faturado</th>
                        <th>Total</th>
                        <th>Esperado</th>
                        <th>Situação</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach($data AS $row)
                        <?php
                            $valor_unitario = $row['valor_contrato'];
                            $producao = $row['producao'];
                            $faturado = $row['faturado'];
                            $quantidade = $row['quantidade_contrato'];
                            $total_producao = ($producao+$faturado) * $valor_unitario;

                            $total_contrato = $valor_unitario * $quantidade;
                        ?>
                        <tr>
                            <td>{!! $row['nome'] !!}</td>
                            <td>{!! number_format($valor_unitario, 2, ',','.') !!}</td>
                            <td>{!! $quantidade !!}</td>
                            <td>{!! $producao !!}</td>
                            <td>{!! $faturado !!}</td>
                            <td>
                                <?php
                                $producao = ($producao+$faturado)-$quantidade;
                                $style_color = ($producao < 0) ? "red" : "green";
                                ?>
                                <span style="color: {!! $style_color !!}">
                                    {!! $producao !!}
                                </span>
                            </td>
                            <td>{!! number_format($total_producao, 2, ',','.') !!}</td>
                            <td>{!! number_format($total_contrato, 2, ',','.') !!}</td>
                            <td>
                                <?php
                                    $situacao = $total_producao-$total_contrato;
                                    $style_color = ($situacao < 0) ? "red" : "green";
                                ?>
                                <span style="color: {!! $style_color !!}">
                                    {!! number_format($total_producao-$total_contrato, 2, ',','.') !!}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                <?php foreach($rows['graph'] AS $k =>  $graph) {?>
                    <?php
                        $linha_cuidado = \App\LinhaCuidado::get($k);
                    ?>
                    Highcharts.chart('container-<?php echo $k; ?>', {
                        title: {
                            text: '<?php echo $linha_cuidado['nome']; ?>'
                        },
                        xAxis: {
                            categories: ['<?php  echo implode("','",array_keys($graph))?>']
                        },
                        yAxis: { // Primary yAxis
                            title: {
                                text: 'Produção'
                            }
                        },
                        series: [{
                            type: 'bar',
                            name: 'Produção',
                            data: [<?php echo implode(",",array_column($graph, 'producao'))?>],
                            dataLabels: {
                                enabled: true
                            }
                        }, {
                            type: 'bar',
                            name: 'Contrato',
                            data: [<?php echo implode(",",array_column($graph, 'contrato'))?>],
                            dataLabels: {
                                enabled: true
                            }
                        },{
                            type: 'bar',
                            name: 'Situação',
                            data: [<?php echo implode(",",array_column($graph, 'situacao'))?>],
                            dataLabels: {
                                enabled: true
                            },
                            color: 'red',
                            marker: {
                                lineWidth: 0.5,
                                lineColor: 'red',
                                fillColor: 'white'
                            }
                        }]
                    });
                <?php } ?>
            </script>
        </div>
    @endforeach
@else
    <div class="alert alert-danger">Sem dados cadastrado no contrato!</div>
@endif