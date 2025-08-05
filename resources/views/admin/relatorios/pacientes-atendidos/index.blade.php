@extends('admin')

@section('content')
    <div class="card">
        <div class="card-heading">
            <h2>
                Pacientes Atendimentos
            </h2>
            <small></small>
            <hr/>

            <div id="">
                <form>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-md-2">
                            {!!Form::textField('periodo_inicial', 'Periodo (Inicial)', date('d/m/Y'), array('class' => 'form-control date'))!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::textField('periodo_final', 'Periodo (Final)', date('d/m/Y'), array('class' => 'form-control date'))!!}
                        </div>
                        <div class="col-md-6"></div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a id="btn-relatorio-ajax" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar-relatorio')}}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <hr />
            <div class="card-body bg-light lt" id="box-grid">
                <div class="alert alert-info"><?php echo e(Lang::get('app.selecione-os-parametros-para-pesquisa')); ?></div>
            </div>
        </div>
    </div>
@stop

@section('script')

@stop