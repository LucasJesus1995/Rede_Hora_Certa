@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                Relatorio de Remarcação (Agendas)
            </h2>
            <small></small>
            <hr />

            <div>
                {!! Form::model(null) !!}
                {!! csrf_field() !!}

                <div class="row">
                    <div class="col-md-2">{!! Form::selectField('ano', \App\Http\Helpers\Util::getAnos(), "Ano", date('Y'), array('class' => 'form-control chosen')) !!}</div>
                    <div class="col-md-2">{!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), "Mês", date('m'), array('class' => 'form-control chosen')) !!}</div>
                    <div class="col-md-7">{!! Form::selectField('unidade', \App\Arenas::Combo(), "Unidade", null, array('class' => 'form-control chosen')) !!}</div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button id="btn-relatorio-ajax" class="btn btn-success waves-effect col-md-12 col-sm-12 col-xs-12 col-lg-12" type="button">Gerar</button>
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