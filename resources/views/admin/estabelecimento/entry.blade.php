@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.listagem-de-dados')}}
      </h2>
      <small>{{Lang::get('description.estabelecimento')}}</small>
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
       {!!Form::model($entry, array('url' => '/admin/estabelecimento', 'class' => 'form-vertical'))!!}
       {!!Form::hidden('id')!!}
        <div class="row">
            <div class="col-md-6">{!!Form::textField('nome', Lang::get('app.nome'), null, array('class' => 'form-control'))!!}</div>
            <div class="col-md-6">{!!Form::selectField('ativo', array('1' => Lang::get('app.sim'), '0' => Lang::get('app.nao')), Lang::get('app.ativo'), null, array('class' => 'form-control'))!!}</div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <hr />
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