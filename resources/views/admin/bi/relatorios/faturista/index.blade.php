<?php
    $mes = 3;
    $ano = 2017;
    $ultimo_dia_mes = date("t", mktime(0,0,0,$mes,'01',$ano));
?>

@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>Relatorio de Produção</h2>
            <small>Produção das (Arenas / Faturistas) finalização de agendas</small>
            <hr />

            <div class="row" id="filtro-relatorio-faturista">
                <div class="col-md-4">
                    {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), null, array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                </div>
                <div class="col-md-2">
                    {!!Form::selectField('forma_faturamento', \App\Http\Helpers\Util::FormaFaturamento() ,  Lang::get('app.faturamento'), 2, array('class' => 'form-control chosen','id'=>'forma_faturamento'))!!}
                </div>
                <div class="col-md-2">
                    {!!Form::selectField('ano', \App\Http\Helpers\Util::getUltimosAnos(10),  Lang::get('app.ano'), date('Y'), array('class' => 'form-control chosen','id'=>'ano'))!!}
                </div>
                <div class="col-md-2">
                    {!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), Lang::get('app.mes'), date('m'), array('class' => 'form-control chosen','id'=>'mes')) !!}
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a id="btn-relatorio-producao-arena-faturistas" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar-relatorio')}}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body bg-light lt" id="box-grid"></div>
    </div>



@stop

@section('script')
    $("#btn-relatorio-producao-arena-faturistas").trigger('click');
@stop