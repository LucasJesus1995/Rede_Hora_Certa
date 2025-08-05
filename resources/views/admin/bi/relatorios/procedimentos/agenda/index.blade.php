@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                Relatório (Agendamento, Produção, Gordura e Absenteísmo)
            </h2>
            <small></small>
            <hr/>

            <div id="">
                {!! Form::model(null) !!}

                <div class="row">
                    <div class="col-md-3">
                        {!!Form::selectField('contrato', \App\Lotes::Combo(), 'Contrato', 7, array('class' => 'form-control chosen'))!!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::selectField('faturamento', \App\Faturamento::Combo(),  'Faturamento', null, array('class' => 'form-control chosen'))!!}
                    </div>
                    <div class="col-md-6">
                        {!!Form::selectField('grupo', \App\SubGrupos::Combo(),  "Grupo", null, array('class' => 'form-control chosen combo-especialidade-profissionais combo-especialidade-procedimentos'))!!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::selectField('especialidade', \App\LinhaCuidado::Combo(),  "Especialidade", null, array('class' => 'form-control chosen combo-especialidade-profissionais combo-especialidade-procedimentos'))!!}
                    </div>
                    <div class="col-md-4">
                        {!!Form::selectField('profissional', [],  "Profissional", null, array('class' => 'form-control chosen profissionais'))!!}
                    </div>
                    <div class="col-md-5">
                        {!!Form::selectField('procedimento', [],  "Procedimentos", null, array('class' => 'form-control chosen procedimentos'))!!}
                    </div>
                </div>

                <div class="form-group align-right" style="">
                    <button id="btn-relatorio-procedimentos-agenda-falta-gordura" class="btn btn-success waves-effect" type="button" style="padding: 6px 50px">Gerar</button>
                </div>

                {!! Form::close() !!}
            </div>

            <hr/>
            <div id="box-grid">
                <div class="alert alert-info">{{Lang::get('app.selecione-os-parametros-para-pesquisa')}}</div>
            </div>

        </div>
        @stop

        @section('script')
            $(".combo-especialidade-profissionais").change();
            {{--$("#btn-relatorio-procedimentos-agenda-falta-gordura").click();--}}
@stop