@extends('admin')

@section('content')


    <div class="card">
        <div class="card-heading">
            <h2>
                Relatório de configurações de procedimentos
            </h2>
            <small></small>
            <hr />

            <div>
                {!! Form::model(null) !!}
                {!! csrf_field() !!}

                <div class="row">
                    <div class="col-md-10">
                        {!! Form::selectField('linha_cuidado', \App\LinhaCuidado::Combo(), Lang::get('app.linha-cuidado'), null, array('class' => 'form-control chosen','id'=>'mes')) !!}
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="margin-top: 24px">
                            <button id="btn-configuracoes-procedimentos" class="btn btn-success waves-effect col-md-12 col-xs-12" type="button">Gerar</button>
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