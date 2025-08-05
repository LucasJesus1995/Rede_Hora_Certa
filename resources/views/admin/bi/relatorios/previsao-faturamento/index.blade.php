@extends('admin')

@section('content')


    <div class="card">
        <div class="card-heading">
            <h2>
                Relatorio de Previsão Faturamento
            </h2>
            <small></small>
            <hr />

            <div id="relatorio-previsao-faturamento">
                {!! Form::model(null) !!}
                {!! csrf_field() !!}

                <div class="row">
                    <div class="col-md-4">
                        {!!Form::selectField('lote', \App\Lotes::Combo(), Lang::get('app.lote'), 7, array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::selectField('linha_cuidado', \App\LinhaCuidado::Combo(), Lang::get('app.linha-cuidado'), 7, array('class' => 'form-control chosen','id'=>'mes')) !!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::selectField('faturamento', \App\Faturamento::Combo(),  Lang::get('app.faturamento'), date('Y'), array('class' => 'form-control chosen','id'=>'faturamento'))!!}
                    </div>
                    <div class="col-md-1">
                        <div class="form-group" style="margin-top: 24px">
                            <button id="btn-previsao-faturamento" class="btn btn-success waves-effect col-md-12" type="button">Gerar</button>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
        </div>

        <hr />
        <div id="box-grid">
            <div class="alert alert-info">{{Lang::get('app.selecione-os-parametros-para-pesquisa')}}</div>
        </div>

    </div>
@stop