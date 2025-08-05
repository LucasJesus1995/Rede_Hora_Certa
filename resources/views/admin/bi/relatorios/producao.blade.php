@extends('admin')

@section('content')
    <?php
    $is_digitador = (App\Http\Helpers\Util::getNivel() == 10);
    ?>
    <div class="card">
        <div class="card-heading">
            <h2>
                Relatorio de Produção
            </h2>
            <small></small>
            <hr/>

            <div id="relatorio-producao">
                <form>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), null, array('class' => 'form-control combo-arena chosen','id'=>'arena')) !!}
                        </div>
                        <div class="col-md-6">
                            {!! Form::selectField('linha_cuidado', array(), Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado','id'=> 'linha_cuidado')) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-<?php echo ($is_digitador) ? 3 : 2?>">
                            {!!Form::textField('data[inicial]', Lang::get('app.data-inicial'), date('d/m/Y'), array('class' => 'form-control date','id'=>'data'))!!}
                        </div>
                        <div class="col-md-<?php echo ($is_digitador) ? 3 : 2?>">
                            {!!Form::textField('data[final]', Lang::get('app.data-final'), date('d/m/Y'), array('class' => 'form-control date','id'=>'data'))!!}
                        </div>
                        @if(!$is_digitador)
                            <div class="col-md-3">
                                {!!Form::selectField('digitador', \App\Http\Helpers\Util::Digitadores(), 'Digitador', null, array('class' => 'form-control chosen'))!!}
                            </div>
                        @endif
                        <div class="col-md-4">
                            {!!Form::selectField('medico', \App\Http\Helpers\Util::Medicos(), Lang::get('app.medico'), null, array('class' => 'form-control chosen'))!!}
                        </div>
                        <div class="col-md-<?php echo ($is_digitador) ? 2 : 1?>">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a id="btn-gerar-relatorio-producao" href="javascript: void(0)" class="btn btn-success form-control">Gerar</a>
                            </div>
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