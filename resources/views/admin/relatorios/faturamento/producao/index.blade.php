@extends('admin')

@section('content')
    <div class="card">
        <div class="card-heading">
            <h2>
                Contrato
            </h2>
            <small>Exporta os contrato com valores e quantidades</small>
            <hr/>

            <div id="">
                <form>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-md-2">
                            {!! Form::selectField('contrato', \App\Lotes::Combo(), "Contrato", 7, array('class' => 'form-control chosen')) !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::selectField('faturamento', \App\Faturamento::Combo(), "Faturamento", null, array('class' => 'form-control chosen','id'=>'faturamento')) !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::selectField('linha_cuidado', \App\LinhaCuidado::Combo(), "Especialidade", null, array('class' => 'form-control chosen','id'=>'linha_cuidado')) !!}
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a id="btn-relatorio-ajax" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar-relatorio')}}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body bg-light lt" id="box-grid">
                <div class="text-center m-b">
                    <div class="alert alert-info">{{Lang::get('app.selecione-os-parametros-para-pesquisa')}}</div>

                    @if(!empty($relatorio))
                        <table class="table table-striped table-responsive table-bordered  bg-light">
                            @foreach($relatorio AS $row)
                                <tr>
                                    <th colspan="100%">{!! $row['linha_cuidado']['nome'] !!}</th>
                                </tr>

                                @if(!empty($row['procedimentos']))
                                    <tr>
                                        <th>Código</th>
                                        <th>Procedimento</th>
                                        <th>Valor</th>
                                        <th>Agendado</th>
                                        <th>Produção</th>
                                        <th>Faturamento</th>
                                    </tr>

                                    <?php
                                    $_faturamento = \App\Procedimentos::getFaturamento($row['linha_cuidado']['id'], $faturamento);
                                    $_producao = \App\Procedimentos::getProducaoPeriodo($row['linha_cuidado']['id'], $periodo['start'], $periodo['end']);
                                    $_agendamento = \App\Procedimentos::getAgendadoPeriodo($row['linha_cuidado']['id'], $periodo['start'], $periodo['end']);

                                    ?>
                                    @foreach($row['procedimentos'] AS $procedimento)
                                        <?php
                                        $_contrato = \App\Procedimentos::getValorProcedimentoContrato($contrato, $procedimento['id']);
                                        ?>
                                        <tr>
                                            <td class="align-left">{!! $procedimento['sus'] !!}</td>
                                            <td class="align-left">{!! $procedimento['nome'] !!}</td>
                                            <td class="align-left">@if(!empty($_contrato->id)) {!! $_contrato->valor_unitario !!} @endif</td>
                                            <td class="align-left">@if(array_key_exists($procedimento['id'], $_agendamento)){!! $_agendamento[$procedimento['id']] !!}@endif</td>
                                            <td class="align-left">@if(array_key_exists($procedimento['id'], $_producao)){!! $_producao[$procedimento['id']] !!}@endif</td>
                                            <td class="align-left">@if(array_key_exists($procedimento['id'], $_faturamento)){!! $_faturamento[$procedimento['id']] !!}@endif</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <th colspan="100%">&nbsp;</th>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    $("select#faturamento option:eq(1)").prop('selected', true);
    $("#faturamento").trigger("chosen:updated");;
@stop