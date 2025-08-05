@extends('admin')

@section('content')
    <div class="card">
        <div class="card-heading">
            <h2>
                Listagem de dados (Pacientes Importados)
            </h2>
            <small></small>
        </div>
        <div class="card-tools">

        </div>
        <div class="card-body bg-light lt">

            {!!Form::open( array('class' => 'form-vertical','method'=>'POST','id'=>'listagem-pacientes-importados'))!!}
            <div class="row">
                <div class="col-md-2">
                    {!!Form::textField('data', Lang::get('app.data'), date('d/m/Y'), array('class' => 'form-control date','id'=>'data'))!!}
                </div>
                <div class="col-md-5">
                    {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), App\Http\Helpers\Util::setCookie('agenda-pesquisa-arena'), array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                </div>
                <div class="col-md-4">
                    {!!Form::selectField('linha_cuidado', [], Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado','id' => 'linha_cuidado'))!!}
                </div>
                <div class="col-md-1">
                    <div class="align-center">
                        <div class="form-group">
                            <label class="" style="display: block;">&nbsp;</label>
                            <a id="btn-relatorio-ajax" class="btn  col-md-12 btn-info waves-effect"><i class="fa fa-search"></i></a>
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

@section('script')

@stop