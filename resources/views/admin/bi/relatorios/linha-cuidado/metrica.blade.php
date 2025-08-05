@extends('admin')
@section('content')
    <div class="card">
        <div class="card-heading">
            <h2>
                {{Lang::get('app.relatorio-linha-cuidado-metrica')}}
            </h2>
            <small></small>
            <hr />
            <div class="row" id="relatorio-linha-cuidado-metrica">
                <div class="col-md-6">
                    {!!Form::selectMultipleField('lote', \App\Lotes::Combo(), Lang::get('app.lote'), null, array('class' => 'form-control chosen','id'=>'lote'))!!}
                </div>
                <div class="col-md-2">
                    {!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), Lang::get('app.mes'), date('m'), array('class' => 'form-control chosen','id'=>'mes')) !!}
                </div>
                <div class="col-md-2">
                    {!!Form::selectField('ano', \App\Http\Helpers\Util::getUltimosAnos(10),  Lang::get('app.ano'), date('Y'), array('class' => 'form-control chosen','id'=>'ano'))!!}
                </div>
                {{--<div class="col-md-2">--}}
                    {{--{!!Form::selectField('status', ['6'=>'Faturado', '10'=>'Previsão'],  Lang::get('app.status'), 6, array('class' => 'form-control chosen','id'=>'status'))!!}--}}
                {{--</div>--}}
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a id="btn-gerar-relatorio-linha-cuidado-metrica" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar-relatorio')}}</a>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body bg-light lt" id="box-grid">
            <div class="text-center m-b">
                <div class="alert alert-info">{{Lang::get('app.selecione-os-parametros')}}</div>
            </div>
        </div>
    </div>
@stop
@section('script')
    $(".combo-arena").change();
@stop