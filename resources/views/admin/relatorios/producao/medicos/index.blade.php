@extends('admin')

@section('content')
    <div class="card">
        <div class="card-heading">
            <h2>
                Produção Médica
            </h2>
            <small>Exporta a produção médica por unidade, especialidade e procedimento</small>
            <hr/>

            <div id="">
                <form>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-md-2">
                            {!!Form::selectField('ano', \App\Http\Helpers\Util::getUltimosAnos(2),  Lang::get('app.ano'), date('Y'), array('class' => 'form-control chosen'))!!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), Lang::get('app.mes'), date('m'), array('class' => 'form-control chosen')) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::selectField('arena', \App\Arenas::Combo(), "Arena", null, array('class' => 'form-control chosen')) !!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::selectField('medico', \App\Profissionais::ComboMedicos(), "Médico", null, array('class' => 'chosen medico'))!!}
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

            <hr />
            <div class="card-body bg-light lt" id="box-grid">
                <div class="alert alert-info"><?php echo e(Lang::get('app.selecione-os-parametros-para-pesquisa')); ?></div>
            </div>
        </div>
    </div>
@stop

@section('script')

@stop