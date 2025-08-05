@if(!empty($detalhado))
    <div ng-controller="ChartCtrl">
        <div class="row">
            <div class="col-md-12">
                <div class="panel no-border">
                    @if(!empty($graph_all))
                        {!! $graph_all->render() !!}
                    @endif
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel no-border">
                    <table class="table table-striped table-responsive table-bordered  bg-light " id="table-atendimento-medico-faturamento" >
                        <thead>
                            <tr role="row" class="blue-grey-100">
                                <th>{!! Lang::get('app.arenas') !!}</th>
                                <th>{!! Lang::get('app.agendados') !!}</th>
                                <th>{!! Lang::get('app.digitador') !!}</th>
                                <th>{!! Lang::get('app.faturista') !!}</th>
                                <th>{!! Lang::get('app.recepcao') !!}</th>
                                <th>{!! Lang::get('app.aderencia-digitador') !!}</th>
                                <th>{!! Lang::get('app.aderencia-recepcao') !!}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalhado AS $key => $line)
                                <tr role="row" class="blue-grey-100">
                                    <th>{!! $key !!}</th>
                                    <td>{!! $line['agendados'] !!}</td>
                                    <td>{!! $line['digitador'] !!}</td>
                                    <td>{!! $line['faturista'] !!}</td>
                                    <td>{!! $line['recepcao'] !!}</td>
                                    <td>{!! \App\Http\Helpers\Util::getPorcentagem($line['digitador'], $line['atendimento']) !!}%</td>
                                    <td>{!! \App\Http\Helpers\Util::getPorcentagem($line['recepcao'], $line['atendimento']) !!}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


    <script>
        setTimeout(function(){
            jQuery("g[class*='creditgroup']").remove();
            jQuery("g[class*='datalabel']").find('tspan').remove();
        }, 500);
    </script>
@else
    <div class='panel bg-danger pos-rlt'>
        <span class='arrow top  b-danger '></span>
        <div class='panel-body'>{!! Lang::get('grid.nenhum-registro-encontrado') !!}</div>
    </div>
@endif
