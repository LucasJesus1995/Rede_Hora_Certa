@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                {{Lang::get('app.listagem-de-dados')}}
            </h2>
        </div>
        <div class="card-tools">
            <ul class="list-inline">
                <li class="dropdown">
                    <a class="md-btn md-flat md-btn-circle waves-effect" data-toggle="dropdown" md-ink-ripple="" aria-expanded="false">
                        <i class="mdi-navigation-more-vert text-md"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">
                        <li><a href="" class="btn-back-listagem ">{{Lang::get('app.listagem')}}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="card-body bg-light " id="">

            {!!Form::model($entry, array('url' => '/admin/condutas', 'class' => 'form-vertical'))!!}
            {!!Form::hidden('id')!!}
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            {!!Form::selectField('especialidade', \App\LinhaCuidado::Combo(), 'Especialidade', null, array('class' => 'form-control chosen'))!!}
                        </div>
                        <div class="col-md-4">
                            {!!Form::selectField('tipo_atendimento', \App\TipoAtendimento::Combo(), 'Tipo de Agendamento', null, array('class' => 'form-control chosen'))!!}
                        </div>
                        <div class="col-md-4">
                            {!!Form::textField('nome', 'Nome', null, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            {!!Form::selectField('regulacao', \App\Http\Helpers\Util::Ativo(), 'Regulação', null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-6">
                            {!!Form::textField('detalhes', 'Detalhes', null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::selectField('valida_regulacao', \App\Http\Helpers\Util::Ativo(), 'Valida Regulação', null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::selectField('ativo', \App\Http\Helpers\Util::Ativo(), 'Ativo', null, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn-back-listagem btn btn-default waves-effect" type="button">{{Lang::get('app.cancelar')}}</button>
                        <button class="btn-submit btn btn-success waves-effect" type="button">{{Lang::get('app.salvar')}}</button>
                    </div>
                </div>
            </div>
            {!!Form::close()!!}
        </div>

    </div>



@stop

@section('script')
@stop