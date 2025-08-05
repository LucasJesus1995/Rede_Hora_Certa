@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.listagem-de-dados')}}
      </h2>
      <small>{{Lang::get('description.usuarios')}}</small>
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
       {!!Form::model($entry, array('url' => '/admin/usuarios', 'class' => 'form-vertical'))!!}
       {!!Form::hidden('id')!!}
         <div class='row'>
           <div class='col-md-6'>
                {!!Form::textField('name', Lang::get('app.nome'), null, array('class' => 'form-control'))!!}

                <div class='row'>
                    <div class='col-md-12'>
                        {!!Form::textField('email', Lang::get('app.email'), null, array('class' => 'form-control lowercase'))!!}
                    </div>
                </div>
           </div>
           <div class='col-md-6'>
            <div class="row">
                <div class='col-md-6'>
                    {!!Form::selectField('level', \App\Roles::Combo(), Lang::get('app.perfil-acesso'), null, array('class' => 'form-control chosen'))!!}
                </div>
                <div class='col-md-6'>
                    {!!Form::selectField('lote', \App\Lotes::where('ativo', 1)->lists('nome','id')->toArray(), 'Contrato', null, array('class' => 'form-control chosen'))!!}
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>{!!Form::passwordField('password', Lang::get('app.senha'), null, array('class' => 'form-control'))!!}</div>
                <div class='col-md-6'>{!!Form::selectField('active',\App\Http\Helpers\Util::Ativo(), Lang::get('app.ativo'), null, array('class' => 'form-control '))!!}</div>
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
    $(".combo-profissional").change();
@stop