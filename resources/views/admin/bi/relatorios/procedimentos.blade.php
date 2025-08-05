@extends('admin')
@section('content')
    <div class="card">
        <div class="card-heading">
            <h2>
                {{Lang::get('app.relatorio-procedimento')}}
            </h2>
            <small>{{Lang::get('description.relatorio-procedimento')}}</small>
            <hr />
            <div class="row" id="bparelatorio-procedimentos">
                <div class="col-md-6">
                    {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), null, array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                </div>
                <div class="col-md-2">
                    {!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), Lang::get('app.mes'), date('m'), array('class' => 'form-control chosen','id'=>'mes')) !!}
                </div>
                <div class="col-md-2">
                    {!!Form::selectField('ano', \App\Http\Helpers\Util::getUltimosAnos(10),  Lang::get('app.ano'), date('Y'), array('class' => 'form-control chosen','id'=>'ano'))!!}
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a id="btn-gerar-relatorio-procedimentos" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar-relatorio')}}</a>
                    </div>
                </div>
                <div class="col-md-4">
                    {!!Form::selectField('linha_cuidado', array(), Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado','id'=> 'linha_cuidado'))!!}
                </div>
                <div class="col-md-5">
                    {!!Form::selectField('medico',  \App\Http\Helpers\Util::Medicos(),  Lang::get('app.medico'), null, array('class' => 'form-control chosen','id'=>'medico'))!!}
                </div>
                <div class="col-md-3">
                    {!!Form::selectField('finalizacao',  \App\Http\Helpers\Util::getTipoFinalizacaoRelatorioAtendimento(),  Lang::get('app.finalizacao'), null, array('class' => 'form-control chosen','id'=>'finalizacao'))!!}
                </div>
            </div>
        </div>
        <div class="card-body bg-light lt" id="box-grid">
            <div class="text-center m-b">
                <div class="alert alert-info">{{Lang::get('app.selecione-os-parametros-e-clique-gerar-relatorio')}}</div>
            </div>
        </div>
    </div>
@stop
@section('script')
    $(".combo-arena").change();

    $("#bparelatorio-procedimentos #finalizacao option[value='']").each(function() {
        $(this).html("Digitador e Faturista");
    });

    $(".chosen").trigger("chosen:updated");
@stop