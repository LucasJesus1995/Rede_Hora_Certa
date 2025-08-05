@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                Relatorio de Receita Arenas
            </h2>
            <small></small>
            <hr />

            <div>
                {!! Form::model(null) !!}
                {!! csrf_field() !!}

                <div class="row">
                    <div class="col-md-6">
                        {!!Form::selectField('contrato', \App\Lotes::Combo(), "Contrato", null, array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                    </div>
                    <div class="col-md-4">
                        {!!Form::selectField('faturamento', \App\Faturamento::Combo(),  Lang::get('app.faturamento'), date('Y'), array('class' => 'form-control chosen','id'=>'faturamento'))!!}
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="margin-top: 24px">
                            <button class="btn btn-success waves-effect col-md-12 col-sm-12 col-xs-12 col-lg-12 submit-model" type="button">Gerar</button>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>

            <hr />
            <div id="box-grid" class="box-receita-arena">
                <div class="alert alert-info">{{Lang::get('app.selecione-os-parametros-para-pesquisa')}}</div>
            </div>

        </div>
    </div>
@stop