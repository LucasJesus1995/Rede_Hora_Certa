@extends('admin')

@section('content')


  <div class="card">
    <div class="card-heading">
      <h2>
        Atendimentos não faturado
      </h2>
      <small>Listagem dos atendimentos que não foram faturados que o status do atendimentos igual a "Faturado Digitador"</small>
      <hr />

    <div id="">
        <form>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="col-md-3">
                    {!! Form::selectField('arena', \App\Arenas::Combo(), "Unidade", null, array('class' => 'form-control chosen  combo-arena','id'=>'arena')) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::selectField('procedimento', \App\Procedimentos::ComboProcedimentoAgenda(), 'Procedimentos', null, array('class' => 'form-control chosen')) !!}
                </div>
                <div class="col-md-4">
                    {!!Form::selectField('medico', \App\Http\Helpers\Util::Medicos(), 'Medicos', null, array('class' => 'form-control chosen','id'=>'medico'))!!}
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
        </div>
    </div>
  </div>
@stop

@section('script')
    $(".combo-arena").change();
@stop