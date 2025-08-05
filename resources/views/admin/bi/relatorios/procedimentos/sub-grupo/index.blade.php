@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                Relatório de procedimentos por sub-grupo
            </h2>
            <small></small>
            <hr />

            <div id="relatorio-faturamento-sub-grupo">
                {!! Form::model(null) !!}
                {!! csrf_field() !!}

                <div class="row">
                    <div class="col-md-6">
                        {!!Form::selectField('lote', \App\Lotes::Combo(), Lang::get('app.lote'), null, array('class' => 'form-control chosen'))!!}
                    </div>
                    <div class="col-md-4">
                        {!!Form::selectField('faturamento', \App\Faturamento::Combo(),  Lang::get('app.faturamento'), date('Y'), array('class' => 'form-control chosen'))!!}
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="margin-top: 24px">
                            <button id="btn-relatorio-faturamento-sub-grupo" class="btn btn-success waves-effect col-md-12 col-xs-12" type="button">Gerar</button>
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