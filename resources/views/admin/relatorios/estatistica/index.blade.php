@extends('admin')

@section('content')
  <div class="card">
    <div class="card-heading">
      <h2>
          Estatistica
      </h2>
      <small>Relatório estatística de atendimento</small>
      <hr />

    <div id="">
        <form>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            "<div class="row">
                <div class="col-md-2">
                    {!!Form::selectField('ano', \App\Http\Helpers\Util::getUltimosAnosRetroativo(1),  "Ano", date('Y'), array('class' => 'form-control chosen','id'=>'ano'))!!}
                </div>
                <div class="col-md-2">
                    {!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), "Mês", date('m'), array('class' => 'form-control chosen','id'=>'mes')) !!}
                </div>
                <div class="col-md-5">
                    {!!Form::selectField('arena', \App\Arenas::Combo(), "Arena", null, array('class' => 'form-control chosen','id'=>'arena'))!!}
                </div>
                <div class="col-md-2">
                    {!! Form::selectField('tipo', \App\Http\Helpers\Util::TipoRelatorio(), "Tipo", 0, array('class' => 'form-control chosen combo-arena','id'=>'arena')) !!}
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a id="btn-relatorio-ajax" href="javascript: void(0)" class="btn btn-success form-control">Gerar</a>
                    </div>
                </div>
            </div>"
        </form>
    </div>

    <div class="card-body bg-light lt" id="box-grid">
        <div class="text-center m-b">
          <div class="alert alert-info">{{Lang::get('app.selecione-os-parametros-para-pesquisa')}}</div>
        </div>
    </div>
  </div>
@stop