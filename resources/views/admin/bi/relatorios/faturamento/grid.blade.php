@if(!empty($relatorio))
    <div class="">
        @foreach($relatorio AS $lote => $data_lote)
            <div class="panel panel-default">
                <div class="panel-heading bg-white font-bold">{{$lote}}</div>
                <div class="panel-body">
                    @foreach($data_lote AS $linha_cuidado => $data_linha_cuidado)
                        <div class="font-bold">{{$linha_cuidado}}</div>
                        <table class="table table-striped table-responsive table-bordered bg-light">
                            <thead>
                            <tr role="row">
                                <th rowspan="2">Procedimento</th>
                                <th rowspan="2" style="width: 80px">Faturado</th>
                                <th rowspan="2" style="width: 80px">%</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $_quantidade = [];
                            $_percentual = [];

                            $sum_linha_cuidado = array_sum(array_column($data_linha_cuidado, 'quantidade'));
                            ?>
                            @foreach($data_linha_cuidado AS $row)
                                <?php
                                $lote_linha_cuidado = \App\LotesLinhaCuidado::getLoteLinhaCuidadoFaturamento($row['lote_id'], $row['linha_cuidado_id'], $row['faturamento_id']);
                                $maximo = !empty($lote_linha_cuidado[0]['maximo']) ? $lote_linha_cuidado[0]['maximo'] : null;
                                $percentual = (!empty($row['quantidade']) && !empty($sum_linha_cuidado)) ? \App\Http\Helpers\Util::getPorcentagem($row['quantidade'], $sum_linha_cuidado) : 0;

                                ?>
                                <tr>
                                    <td>{{$row['procedimentos_nome']}}</td>
                                    <td>{{$row['quantidade']}}</td>
                                    <td nowrap="">{{ $percentual }}%</td>
                                </tr>
                                <?php
                                $_quantidade[] = $row['quantidade'];
                                $_percentual[] = $percentual;
                                ?>
                            @endforeach
                            <tr>
                                <td></td>
                                <td><strong>{!! array_sum($_quantidade)  !!}</strong></td>
                                <td><strong>{!! round(array_sum($_percentual)) !!}%</strong></td>
                            </tr>
                            </tbody>
                        </table>
                    @endforeach

                </div>
            </div>
        @endforeach
    </div>
@else
    <div class='panel bg-danger pos-rlt'>
        <span class='arrow top  b-danger '></span>
        <div class='panel-body'>{!! Lang::get('grid.nenhum-registro-encontrado') !!}</div>
    </div>
@endif