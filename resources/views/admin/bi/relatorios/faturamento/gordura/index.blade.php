<?php
$mes = 3;
$ano = 2017;
$ultimo_dia_mes = date("t", mktime(0, 0, 0, $mes, '01', $ano));
?>

@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>Relatorio de Gordura - Faturamento</h2>
            <small></small>
            <hr/>

            <form>
                <div class="row" id="filtro-relatorio-faturamento-gordura">
                    <div class="col-md-10">
                        {!! Form::selectField('faturamento', \App\Faturamento::Combo(), "Faturamento", null, array('class' => 'form-control chosen','id'=>'faturamento')) !!}
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <a id="btn-relatorio-faturamento-gordura" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar-relatorio')}}</a>
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