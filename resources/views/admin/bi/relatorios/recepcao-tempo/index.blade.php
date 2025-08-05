<?php
    $mes = 3;
    $ano = 2017;
    $ultimo_dia_mes = date("t", mktime(0,0,0,$mes,'01',$ano));
?>

@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>Relatorio de abertura de atendimento</h2>
            <small></small>
            <hr />

            <div class="row" id="relatorio-tempo-recepcao">
                <div class="col-md-2">
                    {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), null, array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                </div>
                <div class="col-md-3">
                    {!! Form::selectField('linha_cuidado', [], Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado chosen','id' => 'linha_cuidado')) !!}
                </div>
                <div class="col-md-2">
                    {!!Form::selectField('ano', \App\Http\Helpers\Util::getUltimosAnos(10),  Lang::get('app.ano'), date('Y'), array('class' => 'form-control chosen','id'=>'ano'))!!}
                </div>
                <div class="col-md-2">
                    {!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), Lang::get('app.mes'), date('m'), array('class' => 'form-control chosen','id'=>'mes')) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::selectField('exportar', ['1'=>'Excel'], Lang::get('app.exportar'), null, array('class' => 'form-control chosen','id' => 'exportar')) !!}
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a id="btn-relatorio-tempo-recepcao" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar')}}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body bg-light lt" id="box-grid"></div>
    </div>


@stop

@section('script')
    $("#btn-relatorio-tempo-recepcao").click();
@stop