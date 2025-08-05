@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.listagem-de-dados')}}
      </h2>
      <small>{{Lang::get('description.profissional')}}</small>
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
       {!!Form::model($entry, array('url' => '/admin/profissionais', 'class' => 'form-vertical'))!!}
       {!!Form::hidden('id')!!}
        <div class="row">
            <div class="col-md-4">
              {!!Form::selectField('type', \App\Http\Helpers\Util::TypeProfissional(), Lang::get('app.tipo_profissional'), null, array('class' => 'form-control'))!!}
              {!!Form::textField('nome', Lang::get('app.nome'), null, array('class' => 'form-control'))!!}
               <div class="row">
                    <div class="col-md-6">{!!Form::textField('cns', Lang::get('app.cns'), null, array('class' => 'form-control'))!!}</div>
                    <div class="col-md-6">{!!Form::textField('cro', Lang::get('app.cro'), null, array('class' => 'form-control'))!!}</div>
               </div>
              {!!Form::textField('cpf', Lang::get('app.cpf'), null, array('class' => 'form-control cpf'))!!}
              {!!Form::selectField('ativo', \App\Http\Helpers\Util::Ativo(), Lang::get('app.ativo'), null, array('class' => 'form-control'))!!}
            </div>
            <div class="col-md-4">
              {!!Form::selectMultipleField('cbo', \App\Cbo::Combo(), Lang::get('app.cbo'), null, array('class'=>'no-style form-control','style'=>'height: 110px'))!!}      
              {!!Form::selectMultipleField('arena', \App\Arenas::Combo(), Lang::get('app.arena'), null, array('class'=>'no-style form-control','style'=>'height: 180px'))!!}              
            </div>
            <div class="col-md-4">
              {!!Form::selectMultipleField('linha_cuidado', \App\LinhaCuidado::Combo(), Lang::get('app.linha-cuidado'), null, array('class'=>'no-style form-control','style'=>'height: 330px'))!!}
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