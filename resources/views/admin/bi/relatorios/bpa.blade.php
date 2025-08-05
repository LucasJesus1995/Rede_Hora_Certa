<?php

?>
@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.relatorio-bpa')}}
      </h2>
      <small>{{Lang::get('description.relatorio-bpa')}}</small>
      <hr />

      <div class="row" id="relatorio-bpa">
        <div class="col-md-5">
            {!!Form::selectField('faturamento', \App\Faturamento::ComboFaturamentoFinalizado(), Lang::get('app.faturamento'), null, array('class' => 'form-control','id'=>'faturamento'))!!}
        </div>
          <div class="col-md-5">
              {!!Form::selectField('lote', \App\Lotes::Combo(), Lang::get('app.lotes'), null, array('class' => 'form-control','id'=>'lote'))!!}
          </div>
        <div class="col-md-2">
            <label>&nbsp;</label><br />
            <a id="btn-gerar-relatorio-bpa" href="javascript: void(0)" class="btn btn-success col-md-12">{{Lang::get('app.gerar-arquivo')}}</a>
        </div>
      </div>
    </div>

    <div class="card-body bg-light lt" id="box-grid">
        <div class="text-center m-b">
          <div class="alert alert-info">{{Lang::get('app.selecione-os-parametros-e-clique-gerar-arquivo')}}</div>
        </div>
    </div>
  </div>

@stop

@section('script')
    $(".combo-arena").change();
@stop