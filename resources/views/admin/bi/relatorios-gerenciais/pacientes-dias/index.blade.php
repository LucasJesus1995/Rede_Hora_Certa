@extends('admin')

@section('content')
    <div class="card">
        <div class="card-heading">
            <h2>
                Relatórios (Pacientes Dias)
            </h2>
            <small></small>
            <hr />

            <div id="relatorio-paciente-dia">
                <form>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row" >
                        <div class="col-md-2">
                            {!! Form::selectField('ano', \App\Http\Helpers\Util::getAnos(), Lang::get('app.ano'), date('Y'), array('class' => 'form-control  chosen','id'=>'ano')) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), Lang::get('app.mes'), date('m'), array('class' => 'form-control  chosen','id'=>'mes')) !!}
                        </div>
                        <div class="col-md-4">
                            {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), null, array('class' => 'form-control chosen','id'=>'arena'))!!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::selectField('medico', \App\Http\Helpers\Util::Medicos(), Lang::get('app.medico'), null, array('class' => 'form-control chosen','id'=>'medico'))!!}
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a id="btn-gerar-relatorio-paciente-dia" class="btn col-md-12 btn-success waves-effect waves-effect">
                                    <i class="fa fa-search"></i>
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
    </div>
@stop

@section('script')
    $("#btn-gerar-relatorio-paciente-dia").click();
    removeSelectDefault('ano');
    removeSelectDefault('mes');
@stop