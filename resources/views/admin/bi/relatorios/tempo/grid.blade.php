@if(!empty($relatorio))
    <div class="streamline b-l b-accent m-b m-l">
        @foreach($relatorio AS $key => $days)

            <div class="sl-item sl-item-md">
                <div class="sl-icon">
                    <i class="fa fa-check text-muted-dk"></i>
                </div>
                <div class="sl-content">
                    <div class="text-muted-dk">{{$key}}</div>
                    <p>&nbsp;</p>
                </div>
            </div>

            @foreach($days AS $day => $rows)
                <div class="sl-item b-primary b-l">
                    <div class="sl-content">
                        <div class="text-muted-dk">{{$day}}</div>
                            <p>
                            <table class="table table-striped table-responsive table-bordered  bg-light " >
                                <thead>
                                    <tr role="row">
                                        <th>Agenda</th>
                                        <th>Atendimento</th>
                                        <th>Preferencial</th>
                                        <th>Especialidade</th>
                                        <th>Recepção</th>
                                        <th>Médico</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sum_recepcao = null;
                                        $sum_medicina = null;
                                    ?>
                                    @foreach($rows AS $row)
                                        <?php
                                            $recepcao = \App\Http\Helpers\Util::TempoCalculo($row['recepcao_in'], $row['recepcao_out']);
                                            $medicina = \App\Http\Helpers\Util::TempoCalculo($row['medico_in'], $row['medico_out']);

                                            if($recepcao)
                                                $sum_recepcao[] = $recepcao;

                                            if($medicina)
                                                $sum_medicina[] = $medicina;

                                        ?>
                                        <tr class=''>
                                            <td>{{$row['agenda']}}</td>
                                            <td>{{$row['atendimento']}}</td>
                                            <td>@if($row['preferencial']) Sim @endif</td>
                                            <td>{{$row['linha_cuidado']}}</td>
                                            <td>@if($recepcao) {{ gmdate("H:i:s", $recepcao) }} @endif</td>
                                            <td>@if($medicina) {{ gmdate("H:i:s", $medicina) }} @endif</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="align-center"><span class="align-center">Recepção <strong>{{count($sum_recepcao)}}</strong> atendimentos (@if(!empty($sum_recepcao) && count($sum_recepcao)) <strong>{{gmdate("H:i:s", (array_sum($sum_recepcao) / count($sum_recepcao)))}}</strong> @endif )</span></td>
                                        <td colspan="3" class="align-center"><span class="align-center">Médico <strong>{{count($sum_medicina)}}</strong> atendimentos (@if(!empty($sum_medicina) && count($sum_medicina)) <strong>{{gmdate("H:i:s", (array_sum($sum_medicina) / count($sum_medicina)))}}</strong> @endif )</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </p>
                    </div>
                </div>
                <?php
                    $sum_recepcao = null;
                    $sum_medicina = null;
                ?>
            @endforeach
        @endforeach
    </div>
@else
    <div class='panel bg-danger pos-rlt'>
        <span class='arrow top  b-danger '></span>
        <div class='panel-body'>{!!Lang::get('grid.nenhum-registro-encontrado')!!}</div>
    </div>
@endif