@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                Exportação de Pacientes
            </h2>
            <small></small>
            <hr />

            <div id="relatorio-exportacao">
                <form>
                    <div class="row">
                        <div class="col-md-7">
                            {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arena'), null, array('class' => 'form-control','id'=>'arena'))!!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::selectField('periodo', \App\Http\Helpers\Util::anoMes(), Lang::get('app.periodo')." (agendamento)", date('Y')."-".date('m'), array('class' => 'form-control','id'=>'periodo'))!!}
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a id="btn-gerar-exportacao-pacientes" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar-relatorio')}}</a>
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

@stop