@extends('admin')

@section('content')


  <div class="card">
    <div class="card-heading">
      <h2>
        Laudo (Biopsia)
      </h2>
      <small></small>
      <hr />

    <div id="relatorio-biopsia">
        <form>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row" >
                <div class="col-md-2">
                    {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), null, array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                </div>
                <div class="col-md-2">
                    {!!Form::selectField('linha_cuidado', [], Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado chosen','id' => 'linha_cuidado'))!!}
                </div>
                <div class="col-md-2">
                    {!! Form::selectField('ano', \App\Http\Helpers\Util::getAnos(), Lang::get('app.ano'), date('Y'), array('class' => 'form-control chosen','id'=>'ano')) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), Lang::get('app.mes'), date('m'), array('class' => 'form-control chosen','id'=>'mes')) !!}
                </div>
                <div class="col-md-3">
                    {!!Form::selectField('medico', \App\Http\Helpers\Util::Medicos(), Lang::get('app.medico'), null, array('class' => 'form-control chosen','id'=>'medico'))!!}
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a id="btn-gerar-relatorio-biopsia" href="javascript: void(0)" class="btn btn-success form-control ">
                            <i class="mdi-action-search text-lg "></i>
                        </a>
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