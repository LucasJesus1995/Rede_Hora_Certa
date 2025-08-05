@extends('admin')

@section('content')


  <div class="card">
    <div class="card-heading">
      <h2>
        Contrato
      </h2>
      <small>Exporta os contrato com valores e quantidades</small>
      <hr />

    <div id="">
        <form>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="col-md-10">
                    {!! Form::selectField('contrato', \App\Lotes::Combo(), "Contrato", null, array('class' => 'form-control chosen  combo-arena','id'=>'arena')) !!}
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