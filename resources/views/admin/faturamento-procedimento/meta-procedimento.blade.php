@extends('admin')

@section('content')


  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.faturamento')}}
      </h2>
      <small>{{Lang::get('description.faturamento')}}</small>
      <hr />

      <div class="row" id="relatorio-meta-faturamento">
        <div class="col-md-6">
            {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), null, array('class' => 'form-control','id'=>'arena'))!!}
        </div>
        <div class="col-md-2">
            {!!Form::selectField('ano', \App\Http\Helpers\Util::getUltimosAnosRetroativo(1),  Lang::get('app.ano'), date('Y'), array('class' => 'form-control','id'=>'ano'))!!}
        </div>
        <div class="col-md-2">
            {!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), Lang::get('app.mes'), date('m'), array('class' => 'form-control','id'=>'mes')) !!}
        </div>
        <div class="col-md-2">
            <label>&nbsp;</label><br />
            <a id="btn-meta-procedimento" href="javascript: void(0)" class="btn btn-success">{{Lang::get('app.pesquisar')}}</a>
        </div>
      </div>
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