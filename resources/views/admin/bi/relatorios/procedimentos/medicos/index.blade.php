@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                Relatório de procedimento (produção) por médico e especialidade
            </h2>
            <small></small>
            <hr />

            <div id="">
                {!! Form::model(null) !!}

                    <div class="row">
                        <div class="col-md-3">
                            {!!Form::selectField('contrato', \App\Lotes::Combo(), 'Contrato', 7, array('class' => 'form-control chosen'))!!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::selectField('faturamento', \App\Faturamento::Combo(),  'Faturamento', 21, array('class' => 'form-control chosen'))!!}
                        </div>
                        <div class="col-md-6">
                            {!!Form::selectField('especialidade', \App\LinhaCuidado::Combo(),  "Especialidade", 7, array('class' => 'form-control chosen combo-especialidade-profissionais combo-especialidade-procedimentos'))!!}
                        </div>
                        <div class="col-md-4">
                            {!!Form::selectField('profissional', [],  "Profissional", null, array('class' => 'form-control chosen profissionais'))!!}
                        </div>
                        <div class="col-md-6">
                            {!!Form::selectField('procedimento', [],  "Procedimentos", null, array('class' => 'form-control chosen procedimentos'))!!}
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" style="margin-top: 24px">
                                <button id="btn-faturamento-procedimentos-medico" class="btn btn-success waves-effect col-md-12 col-xs-12" type="button">Gerar</button>
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

@section('script')
    $(".combo-especialidade-profissionais").change();
    $("#btn-faturamento-procedimentos-medico").click();
@stop