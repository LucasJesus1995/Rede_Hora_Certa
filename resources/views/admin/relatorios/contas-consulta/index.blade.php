@extends('admin')

@section('content')
    <div class="card">
        <div class="card-heading">
            <h2>
                Contas Consulta
            </h2>
            <small></small>
        </div>
        <div class="card-tools">

        </div>
        <div class="card-body bg-light lt">

            {!!Form::open( array('class' => 'form-vertical','method'=>'POST','id'=>''))!!}
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            {!!Form::textField('periodo_inicial', 'Período (Inicial)', date('01/m/Y'), array('class' => 'form-control date'))!!}
                        </div>
                        <div class="col-md-6">
                            {!!Form::textField('periodo_final', 'Período (Final)', date('d/m/Y'), array('class' => 'form-control date'))!!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), App\Http\Helpers\Util::setCookie('agenda-pesquisa-arena'), array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                </div>
                <div class="col-md-3">
                    {!!Form::selectField('linha_cuidado', [], Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado','id' => 'linha_cuidado'))!!}
                </div>
                <div class="col-md-1">
                    <div class="align-center">
                        <div class="form-group">
                            <label class="" style="display: block;">&nbsp;</label>
                            <button type="submit" id="btn-relatorio-ajax" class="btn  col-md-12 btn-info waves-effect"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            {!!Form::close()!!}

            <hr/>
            <div id="box-grid">
                <div class="text-center m-b">
                    <div class="alert alert-info"><?php echo e(Lang::get('app.selecione-os-parametros-para-pesquisa')); ?></div>
                </div>
            </div>
        </div>
    </div>

@stop