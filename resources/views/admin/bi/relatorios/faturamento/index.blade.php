@extends('admin')

@section('content')


  <div class="card">
    <div class="card-heading">
      <h2>
        Faturamento (Mensal)
      </h2>
      <small></small>
      <hr />

    <div id="relatorio-faturamento">
        <form>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row" >
                <div class="col-md-6">
                    {!! Form::selectField('faturamento', \App\Faturamento::Combo(), Lang::get('app.faturamento'), null, array('class' => 'form-control chosen','id'=>'faturamento')) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::selectField('lote', \App\Lotes::Combo(), Lang::get('app.lotes'), null, array('class' => 'form-control chosen', 'id'=>'lote')) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    {!! Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arena'), null, array('class' => 'form-control chosen  combo-arena','id'=>'arena')) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::selectField('linha_cuidado', \App\LinhaCuidado::Combo(), Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado','id'=>'linha_cuidado')) !!}
                </div>
                <div class="col-md-4">
                    {!!Form::selectField('medico', \App\Http\Helpers\Util::Medicos(), Lang::get('app.medico'), null, array('class' => 'form-control chosen','id'=>'medico'))!!}
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a id="btn-gerar-relatorio-faturamento" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar-relatorio')}}</a>
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